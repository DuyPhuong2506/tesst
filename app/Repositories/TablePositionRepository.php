<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class TablePositionRepository extends BaseRepository
{
    public $model;
    
    public function getModel()
    {
        return \App\Models\TablePosition::class;
    }
}
