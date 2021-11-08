<?php
namespace App\Services;

use App\Models\Wedding;
use App\Models\EventTimes;

class EventService
{

    public function deleteEventTime($eventId){
        return EventTimes::where('event_id', $eventId)->delete();
    }

    public function createEvent($data){
        $event = Wedding::create($data);
        $event->eventTimes()->createMany($data['event_times']);

        return true;
    }

    public function detailEvent($id){
        $weddingEvent = Wedding::where('id', $id)->with('eventTimes')->first();
        
        return [
            'status' => true,
            'event' => $weddingEvent
        ];
    }

    public function updateEvent($data){
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