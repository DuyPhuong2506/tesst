<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class UserTokenRepository extends BaseRepository
{
    public $model;
    
    public function getModel()
    {
        return \App\Models\UserToken::class;
    }
}