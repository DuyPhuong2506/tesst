<?php
namespace App\Services;

use App\Repositories\TemplateContentRepository;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Str;

class TemplateContentService
{
    protected $templateContentRepo;

    public function __construct(TemplateContentRepository $templateContentRepo)
    {
        $this->templateContentRepo = $templateContentRepo;
    }

    public function getTemplateContents()
    {
        return $this->templateContentRepo
                    ->model
                    ->select([
                        'id', 'name', 'preview_image', 
                        'font_name', 'content', 'status'
                    ])
                    ->get();
    }

    public function createTemplateContent($file, $requestData)
    {
        
        $directory = "templatecontent/";
        $fileName = Str::random(30) . "_" . $file->getClientOriginalName();
        $fileExtension = $file->getClientOriginalExtension();
        $filePath = $directory . $fileName;
        
        $image = Image::make($file)
                      ->resize(700, null, function($constraint){ 
                            $constraint->aspectRatio(); 
                        })
                      ->encode($fileExtension, 90);

        $store = Storage::disk('local')->put($filePath, $image);
        $requestData['preview_image'] = $filePath;

        return $this->templateContentRepo->model->create($requestData);
    }
}