<?php
namespace App\Services;

use App\Repositories\TemplateCardRepository;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Str;

class TemplateCardService
{

    protected $templateCardRepo;

    public function __construct(TemplateCardRepository $templateCardRepo)
    {
        $this->templateCardRepo = $templateCardRepo;
    }

    public function getTemplateCards($type)
    {
        return $this->templateCardRepo
                    ->model
                    ->where('type', $type)
                    ->select([
                        'id', 
                        'name', 
                        'card_path', 
                        'type'
                    ])
                    ->get();
    }

    public function createTemplateCard($requestData, $file)
    {   
        $directory = "templatecard/";
        $fileName = Str::random(30) . "_" . $file->getClientOriginalName();
        $fileExtension = $file->getClientOriginalExtension();
        $filePath = $directory . $fileName;
        
        $image = Image::make($file)
                      ->resize(700, null, function($constraint){ 
                            $constraint->aspectRatio(); 
                        })
                      ->encode($fileExtension, 90);

        $store = Storage::disk('local')->put($filePath, $image);
        $requestData['card_path'] = $filePath;

        return $this->templateCardRepo->model->create($requestData);
    }

}