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
                        ->where(function($q) use($keyword){
                            $q->whereHas('place', function($q) use($keyword){
                                $q->where("name", "LIKE", '%'.$keyword.'%')->where('status', STATUS_TRUE);
                            })->orWhere('place_id', null);
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

    public function deleteEventTime($eventTimeId)
    {
        return EventTimes::find($eventTimeId)->delete();
    }

    public function makeCouple($couple, $weddingEventId)
    {
        $item = [];
        foreach ($couple as $value)
        {
            $username = random_str_az(8).random_str_number(4);
            $password = random_str_az(8).random_str_number(4);

            $coupleContent = [
                'username' => $username,
                'password' => $password,
                'email'    => $value['email'],
                'wedding_id' => $weddingEventId,
                'role' => $value['role'],
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'full_name' => $value['full_name']
            ];
            array_push($item, $coupleContent);
            $sendEmailJob = new SendEventEmailJob($value['email'], $coupleContent);
            dispatch($sendEmailJob);
        }
        Customer::insert($item);
    }

    public function createEvent($data)
    {
        $event = Wedding::create($data);
        #Make couple
        $couple = [
            [
                'email' => $data['groom_email'], 
                'full_name' => $data['groom_name'],
                'role' => Role::GROOM
            ],
            [
                'email' => $data['bride_email'],
                'full_name' => $data['bride_name'],
                'role' => Role::BRIDE
            ]     
        ];
        $this->makeCouple($couple, $event->id);

        return $this->detailEvent($event->id);
    }

    public function detailEvent($id)
    {
        $event = Wedding::where('id', $id)->with(['eventTimes', 'customer'])->first();
        if($event){
            return $event;
        }
        
        return null;
    }

}