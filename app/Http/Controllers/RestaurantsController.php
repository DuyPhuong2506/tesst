<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RestaurantService;
use App\Http\Requests\CreateRestaurantRequest;

class RestaurantsController extends Controller
{
    protected $restaurantSer;

    public function __construct(RestaurantService $restaurantSer)
    {
        $this->restaurantSer = $restaurantSer;
    }

    public function index(Request $request)
    {
        $restaurants = $this->restaurantSer->getAll();
        return $this->respondSuccess($restaurants);
    }

    public function store(CreateRestaurantRequest $request)
    {
        $data = $request->only('name','phone','address','logo_url','greeting_msg');
        $restaurant = $this->restaurantSer->create($data);
        return $this->respondSuccess($restaurant);
    }
}
