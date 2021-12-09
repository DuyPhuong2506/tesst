<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\UpdateTimeTableEventRequest;
use App\Services\TimeTableService;
use Auth;

class WeddingTimeTableController extends Controller
{

    protected $timeTableService;

    public function __construct(TimeTableService $timeTableService)
    {
        $this->timeTableService = $timeTableService;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(UpdateTimeTableEventRequest $request)
    {
        $timeTable = $request->time_table;
        $weddingId = Auth::guard('customer')->user()->wedding_id;
        $data = $this->timeTableService->updateTimeTable($weddingId, $timeTable);

        if($data){
            return $this->respondSuccess($data);
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.event.create_fail'));
    }

    public function destroy($id)
    {
        //
    }
}
