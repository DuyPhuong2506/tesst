<?php
namespace App\Services;

use App\Models\Wedding;
use App\Models\EventTimes;
use App\Models\Customer;
use App\Jobs\SendEventEmailJob;
use App\Constants\Role;
use App\Constants\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class EventService
{

    public function eventList($data)
    {

        $keyword = (isset($data['keyword'])) ? $data['keyword'] : NULL;
        $orderEventDate = (isset($data['event-date'])) ? $data['event-date'] : "";
        $orderCreatedAt = (isset($data['created-at'])) ? $data['created-at'] : "";

        return Wedding::with('place')
                        ->whereHas('place', function(Builder $q) use($keyword){
                            $q->whereRaw("name like '%$keyword'");
                        })
                        ->when(isset($keyword), function ($q) use($keyword) {
                            return $q->orWhereRaw("event_name LIKE '%$keyword%'");
                        })
                        ->when($orderEventDate == 'asc', function ($q){
                            return $q->orderBy("date", 'asc');
                        })
                        ->when($orderEventDate == 'desc', function ($q){
                            return $q->orderBy("date", 'desc');
                        })
                        ->when($orderCreatedAt == 'asc', function ($q){
                            return $q->orderBy("weddings.created_at", 'asc');
                        })
                        ->when($orderCreatedAt == 'desc', function ($q){
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