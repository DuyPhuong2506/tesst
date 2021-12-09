<?php
namespace App\Services;

use App\Jobs\SendEventEmailJob;
use App\Constants\Role;
use App\Constants\EventConstant;
use App\Repositories\EventRepository;
use App\Repositories\CustomerRepository;
use Carbon\Carbon;
use Auth;

class EventService
{
    protected $eventRepo;
    protected $customerRepo;

    public function __construct(
        EventRepository $eventRepo,
        CustomerRepository $customerRepo
    ){
        $this->eventRepo = $eventRepo;
        $this->customerRepo = $customerRepo;
    }

    public function eventList($request)
    {
        $keyword = (isset($request['keyword'])) ? escape_like($request['keyword']) : NULL;
        $orderBy = (isset($request['order_by'])) ? explode('|', $request['order_by']) : [];
        $paginate = (isset($request['paginate'])) ? $request['paginate'] : EventConstant::PAGINATE;

        return $this->eventRepo->model->whereHas('place', function($q){
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
                        ->select(['id', 'title', 'pic_name', 'date', 'place_id'])
                        ->with(['place' => function($q){
                            $q->select('id', 'name');
                        }])
                        ->orderBy('created_at', 'desc')
                        ->paginate($paginate);
    }

    public function updateGreetingMsg($eventId, $message)
    {
        $data = $this->eventRepo->model->where('id', $eventId)->update([
            'greeting_message' => $message
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
        $this->customerRepo->model->insert($item);
    }

    public function createEvent($data)
    {
        $data['ceremony_reception_time'] = (isset($data['ceremony_reception_time'])) 
                                            ? implode('-', $data['ceremony_reception_time'])
                                            : null;
        $data['ceremony_time'] = implode('-', $data['ceremony_time']);
        $data['party_reception_time'] = (isset($data['party_reception_time']))
                                        ? implode('-', $data['party_reception_time'])
                                        : null;
        $data['party_time'] = (count($data['party_time']) > 1)
                              ? implode('-', $data['party_time'])
                              : $data['party_time'][0];
        
        $event = $this->eventRepo->model->create($data);
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

    public function detailEvent($eventId)
    {
        return $this->eventRepo->model->where('id', $eventId)
                    ->with(['eventTimes', 'customers'])
                    ->first();
    }

    public function coupleDetailEvent($weddingId, $coupleId)
    {
        return $this->eventRepo->model->where('id', $weddingId)
                    ->whereHas('customers', function($q) use($coupleId){
                        $q->where('id', $coupleId);
                    })
                    ->with(['eventTimes', 'place'])
                    ->with(['customers' => function($q){
                        $q->where('role', Role::GUEST);
                    }])
                    ->first();
    }

    public function getWeddingEventLivestream($token)
    {   
        $tablePosition = $this->customerRepo->model->where('token', $token)
                                 ->select('id', 'table_position_id', 'full_name')
                                 ->with(['tablePosition' => function($q){
                                    $q->select('id', 'position');
                                 }])
                                 ->first();
        $weddingId = $this->customerRepo->model->where('token', $token)
                             ->select('wedding_id')->first()->wedding_id;
        $data = $this->eventRepo->model->whereHas('customers', function($q) use($token){
                        $q->where('token', $token);
                    })
                    ->with(['place' => function($q) use($weddingId){
                        $q->select('id', 'name')
                          ->with(['tablePositions' => function($q) use($weddingId){
                                $q->select('place_id', 'id', 'position')
                                  ->where('status', STATUS_TRUE)
                                  ->with(['customers' => function($q) use($weddingId){
                                        $q->select('table_position_id', 'full_name')
                                          ->where('role', Role::GUEST)
                                          ->where('wedding_id', $weddingId);
                            }]);
                        }]);
                    }])
                    ->select('id', 'date', 'place_id')
                    ->with(['eventTimes' => function($q){
                        $q->select(['id', 'event_id', 'start', 'end', 'description']);
                    }])
                    ->first();
        
        $data['customer_detail'] = $tablePosition;
        
        return $data;
    }

    public function coupleListGuest($coupleId, $request)
    {
        $keyword = (isset($request['keyword'])) ? escape_like($request['keyword']) : NULL;
        $paginate = (isset($request['paginate'])) ? $request['paginate'] : EventConstant::PAGINATE;

        $weddingId = $this->customerRepo->model->where('id', $coupleId)->first()->wedding_id;
        
        return $this->customerRepo->model->where(function($q) use($weddingId, $keyword){
                            $q->where('wedding_id', $weddingId);
                            $q->where('role', Role::GUEST);
                        })
                        ->where(function($q) use($keyword){
                            $q->orWhere('full_name', 'like', '%'.$keyword.'%');
                            $q->orWhere('email', 'like', '%'.$keyword.'%');
                            $q->orWhere(function($q) use($keyword){
                                $q->whereHas('tablePosition', function($q) use($keyword){
                                    $q->where('position', 'like', '%'.$keyword.'%');
                                });
                            });
                        })
                        ->with('tablePosition')
                        ->paginate($paginate);
    }

    public function dumpCustomerToken()
    {
        $ids = $this->customerRepo->model->select('id')->get();
        foreach ($ids as $key => $value) {
            $token = \Str::random(50);
            $this->customerRepo->model->where('id', $value['id'])->update(['token' => $token]);
        }

        return $this->customerRepo->model->all();
    }

    public function updateEvent($id, $data)
    {
        $data['ceremony_reception_time'] = (isset($data['ceremony_reception_time'])) 
                                            ? implode('-', $data['ceremony_reception_time'])
                                            : null;
        $data['ceremony_time'] = implode('-', $data['ceremony_time']);
        $data['party_reception_time'] = (isset($data['party_reception_time']))
                                        ? implode('-', $data['party_reception_time'])
                                        : null;
        $data['party_time'] = (count($data['party_time']) > 1)
                              ? implode('-', $data['party_time'])
                              : $data['party_time'][0];

        $event = $this->eventRepo->model->find($id);
        $event->update([
            'title' => $data['title'],
            'date' => $data['date'],
            'pic_name' => $data['pic_name'],
            'ceremony_reception_time' => $data['ceremony_reception_time'],
            'ceremony_time' => $data['ceremony_time'],
            'party_reception_time' => $data['party_reception_time'],
            'party_time' => $data['party_time'],
            'place_id' => $data['place_id'],
            'allow_remote' => $data['allow_remote'],
            'guest_invitation_response_date' => $data['guest_invitation_response_date'],
            'couple_edit_date' => $data['couple_edit_date']
        ]);
        
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

        foreach($couple as $key => $value){
            $username = random_str_az(8).random_str_number(4);
            $password = random_str_az(8).random_str_number(4);

            $value = array_merge($value, [
                'username' => $username,
                'password' => $password
            ]);

            $sendEmailJob = new SendEventEmailJob($value['email'], $value);
            dispatch($sendEmailJob);
            
            $event->customers()->updateOrCreate([
                'role' => $value['role'], 
                'wedding_id' => $id
            ], $value);
        }
        
        return $this->detailEvent($id);
    }
}