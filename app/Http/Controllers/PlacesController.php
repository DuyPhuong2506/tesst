<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\PlaceRepository;
use App\Http\Requests\CreatePlaceRequest;
use App\Models\Place;

class PlacesController extends Controller
{
    protected $placeRepo;

    public function __construct(PlaceRepository $placeRepo)
    {
        $this->placeRepo = $placeRepo;
    }

    public function store(CreatePlaceRequest $request)
    {
        $attributes = $request->only('name','restaurant_id');
        $place = $this->placeRepo->create($attributes);
        return $this->respondSuccess($place);
    }

    public function index()
    {
        $places = $this->placeRepo->getAll();
        return $this->respondSuccess($places);
    }

    public function show($id)
    {
        $place = $this->placeRepo->find($id);
        return $this->respondSuccess($place);
    }

    public function update(CreatePlaceRequest $request, $id)
    {
        $attributes = $request->only('name','restaurant_id');
        $place = $this->placeRepo->update($id, $attributes);
        if ($place) {
            return $this->respondSuccess($place);
        }
        return $this->respondError(Response::HTTP_NOT_IMPLEMENTED, 'place can\'t update');
    }

    public function destroy($id)
    {
        
        $isDel = $this->placeRepo->delete($id);
        if ($isDel) return $this->respondSuccess('place is deleted');

        return $this->respondError(Response::HTTP_NOT_IMPLEMENTED, 'place can\'t delete');
    }
}
