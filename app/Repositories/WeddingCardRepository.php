<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class WeddingCardRepository extends BaseRepository
{
    public $model;
    
    public function getModel()
    {
        return \App\Models\WeddingCard::class;
    }
}