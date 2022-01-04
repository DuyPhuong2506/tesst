<?php
namespace App\Services;

use App\Jobs\SendEventEmailJob;
use App\Jobs\SendDoneSeatJob;
use App\Constants\Role;
use App\Constants\EventConstant;
use App\Constants\NotifyPlannerConstant;
use App\Repositories\EventRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\ChannelRepository;
use Carbon\Carbon;
use Auth;
use Str;
use App\Constants\Common;

class EventService
{
    protected $eventRepo;
    protected $customerRepo;
    protected $channelRepo;

    public function __construct(
        EventRepository $eventRepo,
        CustomerRepository $customerRepo,
        ChannelRepository $channelRepo
    ){
        $this->eventRepo = $eventRepo;
        $this->customerRepo = $customerRepo;
        $this->channelRepo = $channelRepo;
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
                        ->where('status', Common::STATUS_TRUE)
                        ->orWhere("title", "like", '%'.$keyword.'%');
                });
                $q->orWhere('place_id', null);
                $q->orWhere("pic_name", "like", '%'.$keyword.'%');
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

    public function updateThankMessage($eventId, $data)
    {
        $data = $this->eventRepo->model->where('id', $eventId)->update($data);

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
        
        $data['groom_email'] = Str::lower($data['groom_email']);
        $data['bride_email'] = Str::lower($data['bride_email']);
        
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
        $weddingDetail = $this->eventRepo->model
            ->where('id', $weddingId)->whereHas('customers', function($q) use($coupleId){
                $q->where('id', $coupleId);
            })
            ->select(
                'id', 'thank_you_message', 'greeting_message', 
                'table_map_image', 'date', 'ceremony_reception_time',
                'ceremony_time', 'party_reception_time', 'party_time',
                'place_id'
            )
            ->first();

        $weddingTimes = $weddingDetail->eventTimes()
            ->select('id', 'start', 'end', 'description')
            ->get();

        $place = $weddingDetail->place()
            ->select('id', 'restaurant_id', 'name')
            ->first();

        $restaurant = $place->restaurant()
            ->select('id', 'contact_name')
            ->first();
                
        return [
            'wedding_detail' => $weddingDetail,
            'wedding_times' => $weddingTimes,
            'place_name' => $place->name,
            'contact_name' => $restaurant->contact_name
        ];
    }

    /*
    | Staff event detail for update UI[AS-150]
    | @param int $staffID
    | @param int $eventID
    */
    public function staffEventDetail($staffID, $weddingID)
    {
        $weddingDetail = $this->eventRepo->model
            ->where('id', $weddingID)
            ->whereHas('place', function($q) use($staffID){
                $q->whereHas('restaurant', function($q) use($staffID){
                    $q->whereHas('user', function($q) use($staffID){
                        $q->where('id', $staffID);
                    });
                });
            })
            ->select(
                'id', 'title', 'pic_name', 'date', 
                'ceremony_reception_time', 'ceremony_time',
                'party_reception_time', 'party_time',
            )
            ->first();
        
        $couple = $weddingDetail->customers()
            ->where('role', Role::GROOM)
            ->orWhere('role', Role::BRIDE)
            ->select('full_name', 'email', 'role')
            ->get();

        return [
            'wedding_detail' => $weddingDetail,
            'couple' => $couple
        ];
    }

    public function getWeddingEventWithBearerToken($customerId)
    {   
        $tablePosition = $this->customerRepo->model->where('id', $customerId)
            ->select('id', 'full_name')
            ->with(['tablePosition' => function($q){
                $q->select('id', 'position');
            }])->first();

        $weddingId = $this->customerRepo
            ->model
            ->where('id', $customerId)
            ->select('wedding_id')->first()->wedding_id;

        $data = $this->eventRepo->model->whereHas('customers', function($q) use($customerId){
                        $q->where('id', $customerId);
                    })
                    ->with(['place' => function($q) use($weddingId){
                        $q->select('id', 'name')
                          ->with(['tablePositions' => function($q) use($weddingId){
                                $q->select('place_id', 'id', 'position')
                                  ->where('status', Common::STATUS_TRUE)
                                  ->with(['customers' => function($q) use($weddingId){
                                        $q->select('full_name', 'id', 'join_status')
                                            ->with(['customerRelative' => function($q) {
                                                $q->select('id', 'first_name', 'last_name', 'relationship', 'customer_id');
                                            }])
                                          ->where('role', Role::GUEST)
                                          ->where('wedding_id', $weddingId);
                            }]);
                        }]);
                    }, 'eventTimes' => function($q){
                        $q->select(['id', 'event_id', 'start', 'end', 'description']);
                    }])
                    ->first();
        
        $data['customer_detail'] = $tablePosition;
        $data['channel_table'] = null;
        $tablePositionId = Auth::guard('customer')->user()->tablePosition()->first()->id ?? null;
        if($tablePositionId && $weddingId) {
            $data['channel_table'] = $this->channelRepo
                ->model
                ->whereWeddingId($weddingId)
                ->whereTablePositionId($tablePositionId)
                ->with(['tableAccount' => function($q) {
                    $q->select('id', 'full_name', 'username');
                }])
                ->first();
        }
        return $data;
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
            'place_id' => $data['place_id']
        ]);
        
        return $this->detailEvent($id);
    }

    public function updateStateLivesteam($request)
    {
        $authId = Auth::guard('customer')->user()->id;
        $customer = $this->customerRepo->model
            ->where('id', $authId)
            ->first();
        if($customer) {
            $event = $this->eventRepo->model->find($customer->wedding_id);
            $data = [];
            if(isset($request['is_livestream'])) {
                $stateLivesteam = is_numeric($request['is_livestream']) ? $request['is_livestream'] : 0; 
                $data['is_livestream'] = $stateLivesteam;
            }
            if(isset($request['is_join_table']) ) {
                $isJoinTable = is_numeric($request['is_join_table']) ? $request['is_join_table'] : 0; 
                $data['is_join_table'] = $isJoinTable;
            }

            $event->update($data);

            return $this->detailEvent($customer->wedding_id);
        }
      
        return false;
    }

    public function notifyToPlanner($weddingID)
    {
        $wedding = $this->eventRepo->model->find($weddingID);
        $place = $wedding->place()->first();
        $restaurant = $place->restaurant()->first();
        $staff = $restaurant->user()->first();

        $wedding->update([
            'is_notify_planner' => NotifyPlannerConstant::SENT
        ]);

        $groomCustomer = $wedding->customers()
            ->where('role', Role::GROOM)
            ->select('full_name')
            ->first();

        $staffEmail = $staff->email;
        $contentEmail = [
            'contactName' => $restaurant->contact_name,
            'groomName' => $groomCustomer->full_name,
            'appURL' => env('APP_URL'),
        ];

        $sendEmailJob = new SendDoneSeatJob($staffEmail, $contentEmail);
        dispatch($sendEmailJob);

        return true;
    }
}