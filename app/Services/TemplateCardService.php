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
        $data = $this->templateCardRepo
                     ->model
                     ->where('type', $type)
                     ->select(['id', 'name', 'card_path', 'card_thumb_path', 'type'])
                     ->get();
        
        return $data;
    }
    
    public function storeFileWeddingTemplateCard($file)
    {   
        $linkS3Thumbnail = null;
        $linkS3 = null;
        $nameFile = null;
        if ($file){
            $nameDirectory = 'templatecard/';
            $fullName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $nameFile = Str::random(10) . '_' . $fullName;
            uploadImageS3($nameDirectory . $nameFile, $file);
            $linkS3 = $nameDirectory . $nameFile;

            if(in_array($extension , ['png', 'jpg', 'jpeg'])){
                $imgThumb = Image::make($file)->fit(300)->stream();
                
                $linkS3Thumbnail = Storage::disk('s3')->put(
                    $nameDirectory.'thumbnail_' . $nameFile,
                    $imgThumb->__toString(),
                );
                $linkS3Thumbnail = 'thumbnail_' . $nameFile;
            }
        }

        return [
            'card_path' => $nameFile,
            'card_thumb_path' => $linkS3Thumbnail,
        ];
    }

    public function createTemplateCard($requestData, $file)
    {
        $imageLink = $this->storeFileWeddingTemplateCard($file);
        $requestData = array_merge($requestData, $imageLink);

        return $this->templateCardRepo->model->create($requestData);
    }

}