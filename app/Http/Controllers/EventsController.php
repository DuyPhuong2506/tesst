<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\EventService;
use App\Http\Requests\WeddingEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Requests\EventIDRequest;

class EventsController extends Controller
{
    protected $eventService;
    
    public function __construct(EventService $eventService){
        $this->eventService = $eventService;
    }

    public function store(WeddingEventRequest $request){
        $data = $request->all();
        if($this->eventService->createEvent($data)){
            return $this->respondSuccess([
                'event'=>$data
            ]);
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to store !');
    }

    public function show($id){
        $data = $this->eventService->detailEvent($id);
        if($data['status']){
            return $this->respondSuccess($data);
        }

        return $this->respondError(Response::HTTP_NOT_FOUND, 'Show detail event failed !');
    }

    public function update(UpdateEventRequest $request, $id){
        $data = $request->all();
        if($this->eventService->updateEvent($data)){
            return $this->respondSuccess([
                'event'=>$data
            ]);
        }
        
        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to update event !');
    }

}