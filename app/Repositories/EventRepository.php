<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class EventRepository extends BaseRepository
{
    public $model;
    
    public function getModel()
    {
        return \App\Models\Wedding::class;
    }
}
