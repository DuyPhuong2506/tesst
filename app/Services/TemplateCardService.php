<?php
namespace App\Services;

use App\Repositories\TemplateCardRepository;
use Illuminate\Support\Facades\Storage;

class TemplateCardService
{

    protected $templateCardRepo;

    public function __construct(TemplateCardRepository $templateCardRepo)
    {
        $this->templateCardRepo = $templateCardRepo;
    }

    public function getTemplateCards()
    {
        return $this->templateCardRepo
                    ->model
                    ->select(['id', 'name', 'card_path', 'type'])
                    ->get();
    }

    public function createTemplateCard($requestData, $file)
    {
        $nameDirectory = 'template_card/';
        $fullName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $nameFile = \Str::random(10) . '_' . $fullName;
        
        Storage::disk('local')
               ->put($nameDirectory.$nameFile, file_get_contents($file));

        $requestData['card_path'] = $nameDirectory.$nameFile;

        return $this->templateCardRepo->model->create($requestData);
    }

}