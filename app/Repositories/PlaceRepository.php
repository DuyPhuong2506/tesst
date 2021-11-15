<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class PlaceRepository extends BaseRepository
{
    public $model;

    public function getModel()
    {
        return \App\Models\Place::class;
    }
}
