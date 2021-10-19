<?php
namespace App\Services;

use App\Models\Restaurant;

class RestaurantService
{

    public function create($data)
    {
        return Restaurant::create($data);
    }

    public function getAll()
    {
        return Restaurant::all();
    }
}
