<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class TemplateCardRepository extends BaseRepository
{
    public $model;
    
    public function getModel()
    {
        return \App\Models\TemplateCard::class;
    }
}