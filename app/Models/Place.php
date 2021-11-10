<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'restaurant_id',
        'image',
        'image_thumb'
    ];

    /**
     * Get the post that owns the comment.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the post that owns the comment.
     */
    public function tablePositions()
    {
        return $this->hasMany(TablePosition::class, 'place_id', 'id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function positionCameras()
    {
        return $this->hasMany(PositionCamera::class, 'place_id', 'id');
    }
}
