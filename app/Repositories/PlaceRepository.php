<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class PlaceRepository extends BaseRepository
{
    public function getModel()
    {
        return \App\Models\Place::class;
    }
}
