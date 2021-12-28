<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\UpdateTimeTableRequest;
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
        
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($weddingID)
    {
        $data = $this->timeTableService->getListTimeTable($weddingID);
        if($data){
            return $this->respondSuccess($data);
        }

        return $this->respondError(
            Response::HTTP_BAD_REQUEST, __('messages.event.update_fail')
        );
    }

    public function edit($id)
    {
        //
    }

    public function update(UpdateTimeTableRequest $request)
    {
        $requestData = $request->only('wedding_id', 'time_table');
        $data = $this->timeTableService->updateTimeTable($requestData);

        if($data){
            return $this->respondSuccess($data);
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.event.update_fail'));
    }

    public function destroy($id)
    {
        //
    }
}
