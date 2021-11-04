<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\EventService;
use App\Http\Requests\WeddingEventRequest;
use App\Http\Requests\EventIDRequest;

class EventsController extends Controller
{
    protected $eventService;
    
    public function __construct(EventService $eventService){
        $this->eventService = $eventService;
    }

    public function createEvent(WeddingEventRequest $req){
        $data = $req->only(
            'event_name',
            'date',
            'welcome_start',
            'welcome_end',
            'wedding_start',
            'wedding_end',
            'reception_start',
            'reception_end',
            'place_id',
            'groom_name',
            'groom_email',
            'bride_name',
            'bride_email',
            'time_table'
        );
        if($this->eventService->createEvent($data)){
            return $this->respondSuccess($data);
        }
        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to create event!');
    }

    public function detailEvent(EventIDRequest $req){
        $data = $this->eventService->detailEvent($req->id);
        if($data['status']){
            return $this->respondSuccess($data);
        }
        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to get event!');
    }


}