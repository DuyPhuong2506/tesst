<?php
namespace App\Services;

use App\Models\Wedding;
use App\Models\EventTimes;
use App\Models\Customer;
use App\Jobs\SendEventEmailJob;
use App\Constants\Role;
use App\Constants\EventConstant;
use Carbon\Carbon;
use Auth;

class EventService
{

    public function eventList($request)
    {
        $keyword = (isset($request['keyword'])) ? $request['keyword'] : NULL;
        $orderBy = (isset($request['order_by'])) ? explode('|', $request['order_by']) : [];
        $paginate = (isset($request['paginate'])) ? $request['paginate'] : EventConstant::PAGINATE;

        return Wedding::whereHas('place', function($q){
                            $q->whereHas('restaurant', function($q){
                                $q->whereHas('user', function($q){
                                    $q->whereId(Auth::user()->id);
                                });
                            });
                        })
                        ->when(isset($keyword), function($q) use($keyword){
                            $q->whereHas('place', function($q) use($keyword){
                                $q->where("name", "like", '%'.$keyword.'%')
                                  ->where('status', STATUS_TRUE)
                                  ->orWhere("title", "like", '%'.$keyword.'%');
                            })->orWhere('place_id', null);
                        })
                        ->when(count($orderBy) > 0, function ($q) use($orderBy){
                            return $q->orderBy($orderBy[0], $orderBy[1]);
                        })
                        ->with(['place', 'customer'])
                        ->orderBy('created_at', 'desc')
                        ->paginate($paginate);
    }

    public function createTimeTable($data)
    {
        $data = EventTimes::create($data);
        if($data){
            return $data;
        }
        
        return false;
    }

    public function deleteTimeTable($id)
    {
        return EventTimes::where('id', $id)->delete();
    }

    public function updateThankMsg($msg)
    {
        $data = Wedding::where('id', $msg['event_id'])->update([
            'thank_you_message' => $msg['thank_you_message']
        ]);

        if($data){
            return $data;
        }

        return false;
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
        $data['ceremony_reception_time'] = implode('-', $data['ceremony_reception_time']);
        $data['ceremony_time'] = implode('-', $data['ceremony_time']);
        $data['party_reception_time'] = implode('-', $data['party_reception_time']);
        $data['party_time'] = implode('-', $data['party_time']);
        
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

    public function updateEvent($data)
    {
        $eventId = $data['id'];
        $timeEvent = $data['event_times'];
        unset($data['event_times']);
        EventTimes::where('event_id', $eventId)->delete();
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

    public function detailEvent($eventId)
    {
        $event = Wedding::where('id', $eventId)->with(['eventTimes', 'customer'])->first();
        if($event){
            return $event;
        }
        
        return null;
    }

    public function getWeddingEventLivestream($eventId)
    {
        $eventLT = Wedding::whereHas('customer', function($q){
                                $q->whereHas('tablePosition', function($q){
                                    $q->where('status', STATUS_TRUE);
                                });
                            })
                            ->with(['place' => function($q){
                                $q->select('id', 'name')
                                  ->with(['tablePositions' => function($q){
                                        $q->select('place_id', 'id', 'position')
                                          ->with(['customers' => function($q){
                                                $q->select('table_position_id', 'full_name')
                                                  ->where('role', Role::GUEST);
                                            }]);
                                    }]);
                            }])
                            ->with(['eventTimes'])
                            ->whereId($eventId)
                            ->get();
        
        if(count($eventLT) > 0){
            return $eventLT;
        }

        return [];
    }
}