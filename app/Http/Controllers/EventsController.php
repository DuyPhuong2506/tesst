<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\EventService;
use App\Http\Requests\WeddingEventRequest;
use App\Http\Requests\EventIDRequest;
use App\Http\Requests\CreateTimeTableEvent;
use App\Http\Requests\UpdateThankMsg;
use App\Http\Requests\UpdateEventRequest;

class EventsController extends Controller
{
    protected $eventService;
    
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
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

    public function store(WeddingEventRequest $request)
    {
        $requestData = $request->all();
        $eventData = $this->eventService->createEvent($requestData);
        if($eventData){
            return $this->respondSuccess($eventData);
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.event.create_fail'));
    }

    public function show($id)
    {
        $data = $this->eventService->detailEvent($id);
        if($data){
            return $this->respondSuccess($data);
        }

        return $this->respondError(Response::HTTP_NOT_FOUND, __('messages.event.detail_fail'));
    }

    public function update(UpdateEventRequest $request)
    {
        $data = $request->all();
        if($this->eventService->updateEvent($data)){
            return $this->respondSuccess([
                'event'=>$data
            ]);
        }
        
        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.event.update_fail'));
    }

    public function createTimeTable(CreateTimeTableEvent $request)
    {
        $data = $this->eventService->createTimeTable($request->all());
        if($data){
            return $this->respondSuccess($data);
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.event.create_fail'));
    }

    public function deleteTimeTable($id)
    {
        if($this->eventService->deleteTimeTable($id)){
            return $this->respondSuccess([
                'message' => __('messages.event.delete_success')
            ]); 
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.event.delete_fail'));
    }

    public function updateThankMsg(UpdateThankMsg $request)
    {
        $data = $this->eventService->updateThankMsg($request->all());
        if($data){
            return $this->respondSuccess([
                'message' => __('messages.event.update_success'),
            ]); 
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.event.update_fail'));
    }

}