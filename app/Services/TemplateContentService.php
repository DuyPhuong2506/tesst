<?php
namespace App\Services;

use App\Repositories\TemplateContentRepository;
use Illuminate\Support\Facades\Storage;

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
        $nameDirectory = 'template_content/';
        $fullName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $nameFile = \Str::random(10) . '_' . $fullName;
        
        Storage::disk('local')
               ->put($nameDirectory.$nameFile, file_get_contents($file));

        $requestData['preview_image'] = $nameDirectory.$nameFile;

        return $this->templateContentRepo->model->create($requestData);
    }
}