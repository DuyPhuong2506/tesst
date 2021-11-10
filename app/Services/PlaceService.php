<?php
namespace App\Services;

use App\Models\User;
use App\Constants\Role;
use App\Models\Company;
use Mail;
use Str;
use JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Repositories\PlaceRepository;
use App\Models\Place;

class PlaceService
{
    protected $placeRepo;

    public function __construct(PlaceRepository $placeRepo)
    {
        $this->placeRepo = $placeRepo;
    }

    public function storeFilePlace($request)
    {   
        $file = $request->file('image');
        $linkS3Thumbnail = null;
        $linkS3 = null;
        if ($file){
            $nameDirectory = 'places/';
            $fullName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $nameFile = \Str::random(10) . '_' . $fullName;
            uploadImageS3($nameDirectory . $nameFile, $file);
            $linkS3 = Storage::disk('s3')->url($nameDirectory . $nameFile);

            if(in_array($extension , ['png', 'jpg', 'jpeg'])){
                $imgThumb = Image::make($file)->resize(260, 260, function ($constraint) {
                    $constraint->aspectRatio();
                })->stream();
                
                $linkS3Thumbnail = Storage::disk('s3')->put(
                    $nameDirectory.'thumbnail_' . $nameFile,
                    $imgThumb->__toString(),
                    // 'public'
                );
                $linkS3Thumbnail = Storage::disk('s3')->url($nameDirectory.'thumbnail_' . $nameFile);
            }
        }

        return [
            'image' => $linkS3,
            'image_thumb' => $linkS3Thumbnail
        ];
    }

    public function storeFileCameraS3($request)
    {   
        $files = $request->position_cameras;
        $dataCamera = [];
        foreach($files as $key => $camera){
            if($camera['image']){
                $file = $camera['image'];
                $nameDirectory = 'cameras/';
                $fullName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $nameFile = \Str::random(10) . '_' . $fullName;
                uploadImageS3($nameDirectory . $nameFile, $file);
                $linkS3 = Storage::disk('s3')->url($nameDirectory . $nameFile);

                if(in_array($extension , ['png', 'jpg', 'jpeg'])){
                    $imgThumb = Image::make($file)->resize(260, 260, function ($constraint) {
                        $constraint->aspectRatio();
                    })->stream();
                    
                    $linkS3Thumbnail = Storage::disk('s3')->put(
                        $nameDirectory.'thumbnail_' . $nameFile,
                        $imgThumb->__toString(),
                        // 'public'
                    );
                    $linkS3Thumbnail = Storage::disk('s3')->url($nameDirectory.'thumbnail_' . $nameFile);

                    $objectCamera = [
                        'image' => $linkS3,
                        'image_thumb' => $linkS3Thumbnail,
                        'name' => $request->position_cameras[$key]['name']
                    ];
                    
                    array_push($dataCamera, $objectCamera);
                }
            }
        }
        
        return $dataCamera;
    }

    public function storeFileCamera($request)
    {   
        $files = $request->position_cameras;
        $dataCamera = [];
        foreach($files as $key => $camera){
            if($camera['image']){
                $objectCamera = [
                    'image' => $request->position_cameras[$key]['image'],
                    'image_thumb' => $request->position_cameras[$key]['image_thumb'],
                    'name' => $request->position_cameras[$key]['name']
                ];
                
                array_push($dataCamera, $objectCamera);
            }
        }
        
        return $dataCamera;
    }

    public function storePlace($request)
    {   
        $dataImage = $this->storeFilePlace($request);
        $dataCamera = $this->storeFileCamera($request);
        $attributes = $request->only('name','restaurant_id', 'image', 'image_thumb');
        $place = $this->placeRepo->create($attributes);
        $dataTable = [];
        foreach($request->table_positions as $key => $item) {
            $objectTable = [
                'amount_chair' => $item['amount_chair'],
                'position' =>   $item['position'],
                'customer_id' => auth()->id(),
                'status' => STATUS_TRUE
            ];
            array_push($dataTable, $objectTable);
        }

        if($place) {
            foreach($place->tablePositions as $item){
                if($item->image) {
                    $this->removeImageS3($item->image);
                }
                if($item->image) {
                    $this->removeImageS3($item->image_thumb);
                }
            }
            $place->tablePositions()->delete();
            $place->tablePositions()->createMany($dataTable);
            // check delete image
            foreach($place->positionCameras as $item){
                if($item->image) {
                    $this->removeImageS3($item->image);
                }
                if($item->image) {
                    $this->removeImageS3($item->image_thumb);
                }
            }
           
            $place->positionCameras()->delete();
            $place->positionCameras()->createMany($dataCamera);
        }

        return $this->showDetail($place->id);
    }


    public function showDetail($id)
    {   
        $place = Place::whereId($id)->with(['tablePositions', 'positionCameras'])->first();

        return $place;
    }

    public function removeImageS3($link)
    {   
        $host = Storage::disk('s3')->url('../');
        $path = str_replace($host,"", $link);
        Storage::disk('s3')->delete($path);
    }
}
