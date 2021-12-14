<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'bank_accounts';

    protected $fillable = [
        'id', 
        'bank_name',
        'bank_branch',
        'account_number',
        'card_type',
        'holder_name',
        'wedding_card_id'
    ];

    /**
     * Get the weddingCard that owns the BankAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function weddingCard()
    {
        return $this->belongsTo(WeddingCard::class, 'wedding_card_id');
    }

}
