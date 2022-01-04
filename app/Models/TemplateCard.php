<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TemplateCard extends Model
{
    protected $table = 'template_cards';

    protected $fillable = [
        'id',
        'name',
        'card_path',
        'card_thumb_path',
        'type'
    ];

    /**
     * Get all of the comments for the TemplateCard
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function weddingCards()
    {
        return $this->hasMany(WeddingCard::class, 'template_card_id', 'id');
    }

    /**
     * Set the attribulte that owns the WeddingCard
     */
    public function getCardPathAttribute($value)
    {
        return Storage::disk('s3')->url($value);
    }

    /**
     * Set the attribulte that owns the WeddingCard
     */
    public function getCardThumbPathAttribute($value)
    {
        return Storage::disk('s3')->url($value);
    }
}
