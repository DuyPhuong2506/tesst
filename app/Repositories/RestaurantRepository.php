<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class RestaurantRepository extends BaseRepository
{
    public function getModel()
    {
        return \App\Models\Restaurant::class;
    }
}
