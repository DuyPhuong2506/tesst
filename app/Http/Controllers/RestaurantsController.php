<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\RestaurantRepository;
use App\Http\Requests\CreateRestaurantRequest;

class RestaurantsController extends Controller
{
    protected $restaurantRepo;

    public function __construct(RestaurantRepository $restaurantRepo)
    {
        $this->restaurantRepo = $restaurantRepo;
    }

    public function index(Request $request)
    {
        $restaurants = $this->restaurantRepo->getAll();
        return $this->respondSuccess($restaurants);
    }

    public function store(CreateRestaurantRequest $request)
    {
        $data = $request->only('name','phone','address','logo_url','greeting_msg');
        $restaurant = $this->restaurantRepo->create($data);
        return $this->respondSuccess($restaurant);
    }

    public function show($id)
    {
        $restaurant = $this->restaurantRepo->find($id);
        return $this->respondSuccess($restaurant);
    }

    public function update(Request $request, $id)
    {
        $attributes = $request->only('name','phone','address','logo_url','greeting_msg');
        $restaurant = $this->restaurantRepo->update($id, $attributes);
        if ($restaurant) {
            return $this->respondSuccess($restaurant);
        }
        return $this->respondError(Response::HTTP_NOT_IMPLEMENTED, 'restaurant can\'t update');
    }

    public function destroy($id)
    {
        
        $isDel = $this->placeRrestaurantRepoepo->delete($id);
        if ($isDel) return $this->respondSuccess('restaurant is deleted');

        return $this->respondError(Response::HTTP_NOT_IMPLEMENTED, 'restaurant can\'t delete');
    }
}
