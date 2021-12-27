<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerInfo extends Model
{
    protected $table = 'customer_infos';

    protected $fillable = [
        'id',
        'is_only_party',
        'first_name',
        'last_name',
        'relationship_couple',
        'phone',
        'post_code',
        'address',
        'customer_type',
        'task_content',
        'free_word',
        'bank_account_id',
        'is_send_wedding_card',
        'customer_id',
        'email_status',
    ];

    /**
     * Get the user that owns the CustomerInfo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
