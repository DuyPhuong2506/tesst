<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class BankAccountRepository extends BaseRepository
{
    public $model;
    
    public function getModel()
    {
        return \App\Models\BankAccount::class;
    }
}