<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeddingCard extends Model
{
    protected $table = 'wedding_cards';

    protected $fillable = [
        'id',
        'card_url',
        'content',
        'img_url',
        'status',
        'wedding_price',
        'wedding_id',
    ];

    /**
     * Get the user that owns the WeddingCard
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wedding()
    {
        return $this->belongsTo(Wedding::class, 'wedding_id');
    }
}
