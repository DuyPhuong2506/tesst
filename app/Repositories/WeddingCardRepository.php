<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class WeddingTimeTableRepository extends BaseRepository
{
    public $model;
    
    public function getModel()
    {
        return \App\Models\EventTimes::class;
    }
}