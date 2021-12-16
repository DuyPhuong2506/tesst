<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class CustomerTaskRepository extends BaseRepository
{
    public $model;
    
    public function getModel()
    {
        return \App\Models\CustomerTask::class;
    }
}