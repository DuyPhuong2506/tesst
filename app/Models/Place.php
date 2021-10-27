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
        'restaurant_id'
    ];

    /**
     * Get the post that owns the comment.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
