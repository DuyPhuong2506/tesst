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
        $data = $this->templateContentRepo
                     ->model
                     ->select([
                        'id', 'name', 'preview_image', 
                        'font_name', 'content', 'status'
                    ])
                     ->get();

        $disk = Storage::disk('s3');
        for($i = 0; $i < count($data); $i++){
            $previewImage = $disk->url($data[$i]['preview_image']);
            $data[$i]['preview_image'] = $previewImage;
        }
        
        return $data;
        
    }

    public function storeTemplateContentFile($file)
    {
        $linkS3Thumbnail = null;
        if ($file){
            $nameDirectory = 'templatecontent/';
            $fullName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $nameFile = Str::random(10) . '_' . $fullName;
            
            $imgThumb = Image::make($file)->fit(300)->stream();
            
            $linkS3Thumbnail = Storage::disk('s3')->put(
                $nameDirectory.'thumbnail_' . $nameFile,
                $imgThumb->__toString(),
            );
            $linkS3Thumbnail = $nameDirectory.'thumbnail_' . $nameFile;
        }

        return $linkS3Thumbnail;
    }

    public function createTemplateContent($file, $requestData)
    {
        $linkImage = $this->storeTemplateContentFile($file);
        $requestData['preview_image'] = $linkImage;

        return $this->templateContentRepo->model->create($requestData);
    }
}