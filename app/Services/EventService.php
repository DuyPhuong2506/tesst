<?php
namespace App\Services;

use App\Models\Wedding;
use App\Models\EventTimes;
use App\Models\Customer;
use App\Jobs\SendEventEmailJob;
use App\Constants\Role;
use App\Constants\Event;
use Carbon\Carbon;

class EventService
{

    public function eventList($data)
    {

        $keyword = (isset($data['keyword'])) ? $data['keyword'] : "";
        $order_event_date = (isset($data['event-date'])) ? $data['event-date'] : "";
        $order_created_at = (isset($data['created-at'])) ? $data['created-at'] : "";

        return Wedding::join('places', 'places.id', '=', 'weddings.place_id')
                        ->selectRaw('
                            weddings.id as id,
                            weddings.created_at,
                            event_name,
                            name as place_name,
                            groom_name,
                            bride_name,
                            date    
                        ')
                        ->when(isset($keyword), function ($q) use($keyword) {
                            return $q->whereRaw("name LIKE '%$keyword%' OR event_name LIKE '%$keyword%'");
                        })
                        ->when($order_event_date == 'asc', function ($q) use($keyword) {
                            return $q->orderBy("date", 'asc');
                        })
                        ->when($order_event_date == 'desc', function ($q) use($keyword) {
                            return $q->orderBy("date", 'desc');
                        })
                        ->when($order_created_at == 'asc', function ($q) use($keyword) {
                            return $q->orderBy("weddings.created_at", 'asc');
                        })
                        ->when($order_created_at == 'desc', function ($q) use($keyword) {
                            return $q->orderBy("weddings.created_at", 'desc');
                        })
                        ->paginate(Event::PAGINATE);
    }

    public function deleteEventTime($eventId)
    {
        return EventTimes::where('event_id', $eventId)->delete();
    }

    public function makeCouple($coupleEmail, $weddingEventId)
    {
        $item = [];
        foreach ($coupleEmail as $email)
        {
            $username = random_str_az(8).random_str_number(4);
            $password = random_str_az(8).random_str_number(4);

            $coupleContent = [
                'username' => $username,
                'password' => $password,
                'email'    => $email,
                'wedding_id' => $weddingEventId,
                'role' => Role::COUPLE,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ];
            array_push($item, $coupleContent);
            $sendEmailJob = new SendEventEmailJob($email, $coupleContent);
            dispatch($sendEmailJob);
        }

        Customer::insert($item);
    }

    public function createEvent($data)
    {
        $event = Wedding::create($data);
        $event->eventTimes()->createMany($data['event_times']);

        #Send mail to couple
        $coupleEmail = [
            $data['groom_email'],
            $data['bride_email']
        ];
    
        $this->makeCouple($coupleEmail, $event->id);

        return $this->detailEvent($event->id);
    }

    public function detailEvent($id)
    {
        $event = Wedding::where('id', $id)->with('eventTimes')->first();
        if($event){
            return $event;
        }
        
        return null;
    }

    public function updateEvent($data)
    {
        $eventId = $data['id'];
        $timeEvent = $data['event_times'];
        unset($data['event_times']);
        $this->deleteEventTime($eventId);
        if(count($timeEvent) > 0){
            $event = Wedding::find($eventId);
            $event->update($data);
            $event->eventTimes()->createMany($timeEvent);
            
            return true;
        }else{
            Wedding::where('id', $data['id'])->update($data);

            return true;
        }

        return false;
    }

}