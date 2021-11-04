<?php
namespace App\Services;

use App\Models\Wedding;
use App\Models\EventTimes;

class EventService
{

    public function createEventTime($data, $evenId){
        foreach ($data as $value) {
            EventTimes::create([
                'start' => $value['start'],
                'end' => $value['end'],
                'description' => $value['description'],
                'event_id' => $evenId
            ]);
        }
    }

    public function deleteEventTime($eventId){
        return EventTimes::where('event_id', $eventId)->delete();
    }

    public function createEvent($data){
        $event = Wedding::create($data);
        $this->createEventTime($data['time_table'], $data['id']);
        return true;
    }

    public function detailEvent($id){
        $weddingEvent = Wedding::where('id', $id)->get();
        $weddingTimes = EventTimes::where('event_id', $id)->get();
        return [
            'status' => true,
            'weddingEvent' => $weddingEvent,
            'weddingTimes' => $weddingTimes
        ];
    }

    public function updateEvent($data){
        $eventId = $data['id'];
        $timeEvent = $data['time_table'];
        unset($data['time_table']);
        $this->deleteEventTime($eventId);
        if(count($timeEvent) > 0){
            Wedding::where('id', $eventId)->update($data);
            $this->createEventTime($timeEvent, $eventId);
            return true;
        }else{
            Wedding::where('id', $data['id'])->update($data);
            return true;
        }
        return false;
    }

}
