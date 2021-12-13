<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class TemplateContentRepository extends BaseRepository
{
    public $model;
    
    public function getModel()
    {
        return \App\Models\TemplateContent::class;
    }
}
