<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\PlaceRepository;
use App\Services\PlaceService;
use App\Http\Requests\CreatePlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;
use App\Models\Place;

class PlacesController extends Controller
{
    protected $placeRepo;
    protected $placeService;

    public function __construct(PlaceRepository $placeRepo, PlaceService $placeService)
    {
        $this->placeRepo = $placeRepo;
        $this->placeService = $placeService;
    }

    public function store(CreatePlaceRequest $request)
    {
        \DB::beginTransaction();
        try {
            $place = $this->placeService->storePlace($request);
            \DB::commit();
            
            return $this->respondSuccess($place);
        }  catch (\Exception $e) {
            \DB::rollback();
            
            return $this->respondError(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }
    }

    public function index()
    {
        $places = $this->placeService->getAll();
        return $this->respondSuccess($places);
    }

    public function show($id)
    {
        $place = $this->placeService->showDetail($id);
        return $this->respondSuccess($place);
    }

    public function update(UpdatePlaceRequest $request, $id)
    {
        \DB::beginTransaction();
        try {

            $place = $this->placeService->updatePlace($id, $request);
            if ($place) {
                \DB::commit();
                return $this->respondSuccess($place);
            }
            
            \DB::rollback();
            return $this->respondError(Response::HTTP_NOT_IMPLEMENTED, 'place can\'t update');
        }  catch (\Exception $e) {
            \DB::rollback();
            
            return $this->respondError(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }
        
    }

    public function destroy($id)
    {
        
        $isDel = $this->placeRepo->delete($id);
        if ($isDel) return $this->respondSuccess('place is deleted');

        return $this->respondError(Response::HTTP_NOT_IMPLEMENTED, 'place can\'t delete');
    }

    public function getPreSigned(Request $request)
    {
        try {
            $place = $this->placeService->getPreSigned($request);

            return $this->respondSuccess($place);
        }  catch (\Exception $e) {
            return $this->respondError(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }
    }
}
