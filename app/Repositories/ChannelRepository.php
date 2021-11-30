<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

class ChannelRepository extends BaseRepository
{
    public $model;
    
    public function getModel()
    {
        return \App\Models\Channel::class;
    }
}
