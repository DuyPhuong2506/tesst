<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class CustomerRepository extends BaseRepository
{
    public $model;
    
    public function getModel()
    {
        return \App\Models\Customer::class;
    }
}