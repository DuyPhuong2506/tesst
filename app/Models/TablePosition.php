<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TablePosition extends Model
{
    protected $fillable = [
        'amount_chair', 
        'position', 
        'customer_id',
        'status'
    ];

    public function customer()
    {
        return $this->hasMany(Customer::class, 'table_position_id', 'id');
    }
}
