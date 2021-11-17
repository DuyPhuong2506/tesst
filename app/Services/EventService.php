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

    public function eventList($request)
    {

        $keyword = (isset($request['keyword'])) ? $request['keyword'] : NULL;
        $orderBy = (isset($request['order_by'])) ? explode('|', $request['order_by']) : [];
        $paginate = (isset($request['paginate'])) ? $request['paginate'] : Event::PAGINATE;

        return Wedding::with(['place' => function($q){
                            $q->select('id', 'name');
                        }])
                        ->whereHas('place', function($q) use($keyword){
                            $q->where("name", "LIKE", '%'.$keyword.'%');
                        })
                        ->when(isset($keyword), function ($q) use($keyword) {
                            return $q->orWhereRaw("event_name LIKE '%$keyword%'");
                        })
                        ->when(count($orderBy) > 0, function ($q) use($orderBy){
                            return $q->orderBy($orderBy[0], $orderBy[1]);
                        })
                        ->orderBy('created_at', 'desc')
                        ->paginate($paginate);
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