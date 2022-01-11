<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRelative extends Model
{
    protected $table = 'customer_relatives';

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'relationship',
        'customer_id'
    ];

    /**
     * Get the user that owns the CustomerRelatives
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
