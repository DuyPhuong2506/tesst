<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\EventIDRequest;
use App\Http\Requests\UpdateThankMsgRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Requests\EventLiveStreamRequest;
use App\Services\EventService;
use Auth;
use DB;

class EventsController extends Controller
{
    protected $eventService;
    protected $customer;
    protected $user;
    
    public function __construct(
        EventService $eventService
    ){
        $this->eventService = $eventService;
        $this->customer = Auth::guard('customer')->user();
        $this->user = Auth::user();
    }

    public function index(Request $request)
    {
        $requestData = $request->all();
        $responseData = $this->eventService->eventList($requestData);
        
        if($responseData){
            return $this->respondSuccess($responseData);
        }

        return $this->respondError(
            Response::HTTP_BAD_REQUEST, __('messages.event.list_fail')
        );
    }

    public function store(CreateEventRequest $request)
    {
        DB::beginTransaction();
        $eventData = $this->eventService->createEvent($request->all());
        try {
            if($eventData){
                DB::commit();
                return $this->respondSuccess($eventData);
            }
            DB::rollback();
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.event.create_fail'));
        }
    }

    public function show($id)
    {
        $staffID = $this->user->id;
        $data = $this->eventService->staffEventDetail($staffID, $id);
        if($data){
            return $this->respondSuccess($data);
        }

        return $this->respondError(Response::HTTP_NOT_FOUND, __('messages.event.detail_fail'));
    }

    public function coupleDetailEvent()
    {
        $coupleId = $this->customer->id;
        $weddingId = $this->customer->wedding_id;

        $data = $this->eventService->coupleDetailEvent($weddingId, $coupleId);
        if($data){
            return $this->respondSuccess($data);
        }

        return $this->respondError(Response::HTTP_NOT_FOUND, __('messages.event.detail_fail'));
    }

    public function updateThankMessage(UpdateThankMsgRequest $request)
    {
        $message = $request->all();
        $weddingId = $this->customer->wedding_id;
        $data = $this->eventService->updateThankMessage($weddingId, $message);
        if($data){
            return $this->respondSuccess([
                'message' => __('messages.event.update_success'),
            ]); 
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.event.delete_fail'));
    }

    public function getWeddingEventWithBearerToken()
    {   
        $customerId = $this->customer->id;
        $data = $this->eventService->getWeddingEventWithBearerToken($customerId);

        if($data){
            return $this->respondSuccess($data); 
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.event.list_null'));
    }

    public function coupleListGuest(Request $request)
    {
        $coupleId = Auth::guard('customer')->user()->id;
        $data = $this->eventService->coupleListGuest($coupleId, $request);

        if($data){
            return $this->respondSuccess($data); 
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.event.list_null'));
    }

    public function update(UpdateEventRequest $request)
    {
        $requestData = $request->all();
        $id = $request->id;
        DB::beginTransaction();
        try {
            $eventData = $this->eventService->updateEvent($id, $requestData);
            if($eventData){
                DB::commit();
                return $this->respondSuccess([
                    'message' => __('messages.event.update_success')
                ]);
            }
            DB::rollback();
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.event.update_fail'));
        }
    }

    public function updateStateLivesteam(Request $request)
    {
        try {
            $data = $this->eventService->updateStateLivesteam($request->all());
            if($data){
                return $this->respondSuccess($data);
            }
        } catch (\Exception $e) {
            
            return $this->respondError(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }
    }

    public function notifyToPlanner()
    {
        $weddingID = $this->customer->wedding_id;
        $status = $this->eventService->notifyToPlanner($weddingID);
        if($status){
            return $this->respondSuccess([
                'message' => __('messages.mail.send_success')
            ]);
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, [
            'message' => __('messages.mail.send_fail')
        ]);
    }
}