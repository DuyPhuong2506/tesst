<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WeddingCard extends Model
{
    protected $table = 'wedding_cards';

    protected $fillable = [
        'id',
        'template_card_id',
        'content',
        'couple_photo',
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
    
    /**
     * Get all of the comments for the WeddingCard
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class, 'wedding_card_id', 'id');
    }

    /**
     * Get the user that owns the WeddingCard
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function templateCard()
    {
        return $this->belongsTo(TemplateCard::class, 'template_card_id');
    }

    /**
     * Set the attribulte that owns the WeddingCard
     */
    public function getCouplePhotoAttribute($value)
    {
        return Storage::disk('s3')->url($value);
    }
}
