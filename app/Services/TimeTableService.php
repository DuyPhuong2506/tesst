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

    public function updateTimeTable($weddingId ,$data)
    {
        $this->timeTableRepo->model->where('event_id', $weddingId)->delete();
        return $this->eventRepo->model->find($weddingId)->eventTimes()->createMany($data);
    }

}