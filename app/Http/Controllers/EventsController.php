<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\EventService;
use App\Http\Requests\WeddingEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Requests\EventIDRequest;
use App\Http\Requests\EventFilterRequest;

class EventsController extends Controller
{
    protected $eventService;
    
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index()
    {
        $events = $this->eventService->eventList();
        if($events){
            return $this->respondSuccess($events);
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to get event list !');
    }

    public function filter(EventFilterRequest $request)
    {
        $requestData = $request->all();
        $responseData = $this->eventService->filter($requestData);
        
        if($responseData){
            return $this->respondSuccess($responseData);
        }

        return $this->respondError(
            Response::HTTP_BAD_REQUEST, 'Failed to get event list !'
        );
    }

    public function store(WeddingEventRequest $request)
    {
        $requestData = $request->all();
        $eventData = $this->eventService->createEvent($requestData);
        if($eventData){
            return $this->respondSuccess($eventData);
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to store !');
    }

    public function show($id)
    {
        $data = $this->eventService->detailEvent($id);
        if($data){
            return $this->respondSuccess($data);
        }

        return $this->respondError(Response::HTTP_NOT_FOUND, 'Show detail event failed !');
    }

    public function update(UpdateEventRequest $request, $id)
    {
        $data = $request->all();
        if($this->eventService->updateEvent($data)){
            return $this->respondSuccess([
                'event'=>$data
            ]);
        }
        
        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to update event !');
    }

}