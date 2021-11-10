<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PositionCamera extends Model
{
    protected $fillable = [
        'name', 
        'image', 
        'image_thumb',
        'place_id'
    ];
}
