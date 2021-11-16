<?php
namespace App\Services;

use App\Models\Wedding;
use App\Models\EventTimes;
use App\Models\Customer;
use App\Jobs\SendEventEmailJob;
use App\Constants\Role;
use Carbon\Carbon;

class EventService
{

    public function eventList()
    {
        return Wedding::with(['place'])->get();
    }

    public function filter($data)
    {
        $events = Wedding::join('places', 'places.id', '=', 'weddings.place_id')
                        ->select(
                            'event_name', 
                            'date', 
                            'weddings.created_at', 
                            'places.name'
                        );

        if(isset($data['keyword'])){
            $keyword = $data['keyword'];
            $events = $events->whereRaw("
                name LIKE '%$keyword%'
                OR event_name LIKE '%$keyword%'
            ");
        }

        if(isset($data['order_event_date'])){
            if($data['order_event_date'] == 0){
                $events = $events->orderByRaw('date ASC');
            }else{
                $events = $events->orderByRaw('date DESC');
            }
        }
        
        if(isset($data['order_created_at'])){
            if($data['order_created_at'] == 0){
                $events = $events->orderByRaw('weddings.created_at ASC');
            }else{
                $events = $events->orderByRaw('weddings.created_at DESC');
            }
        }

        return $events->paginate(config('app.paginate.event'));

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