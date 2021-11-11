<?php
namespace App\Services;

use App\Models\Wedding;
use App\Models\EventTimes;
use App\Models\Customer;
use App\Jobs\SendEventEmailJob;
use App\Constants\Role;

class EventService
{

    public function deleteEventTime($eventId)
    {
        return EventTimes::where('event_id', $eventId)->delete();
    }

    public function makeCouple($coupleEmail, $weddingEventId)
    {
        foreach ($coupleEmail as $email)
        {
            $coupleContent = [
                'username' => random_str(20),
                'password' => random_str(12),
                'email'    => $email,
                'wedding_id' => $weddingEventId,
                'role' => Role::COUPLE
            ];

            $sendEmailJob = new SendEventEmailJob($email, $coupleContent);
            dispatch($sendEmailJob);
            Customer::insert($coupleContent);
        }
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

        return true;
    }

    public function detailEvent($id)
    {
        $weddingEvent = Wedding::where('id', $id)->with('eventTimes')->first();
        
        return [
            'status' => true,
            'event' => $weddingEvent
        ];
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