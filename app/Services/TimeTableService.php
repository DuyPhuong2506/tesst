<?php
namespace App\Services;

use App\Repositories\WeddingTimeTableRepository;
use App\Repositories\EventRepository;

class TimeTableService
{

    protected $timeTableRepo;
    protected $eventRepo;

    public function __construct(
        EventRepository $eventRepo, 
        WeddingTimeTableRepository $timeTableRepo
    ){
        $this->timeTableRepo = $timeTableRepo;
        $this->eventRepo = $eventRepo;
    }

    public function updateTimeTable($requestData)
    {
        $this->timeTableRepo->model->where('event_id', $requestData['wedding_id'])->delete();
        return $this->eventRepo->model
            ->find($requestData['wedding_id'])
            ->eventTimes()
            ->createMany($requestData['time_table']);
    }

    public function getListTimeTable($weddingID)
    {
        $timeTables = $this->timeTableRepo->model
            ->where('event_id', $weddingID)
            ->get();

        return $timeTables;
    }

}