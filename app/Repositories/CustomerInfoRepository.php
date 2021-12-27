<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class CustomerInfoRepository extends BaseRepository
{
    public $model;
    
    public function getModel()
    {
        return \App\Models\CustomerInfo::class;
    }
}