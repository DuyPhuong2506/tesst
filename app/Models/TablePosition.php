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

    public function customers()
    {
        return $this->belongsToMany(
            Customer::class, 'customer_table', 'table_position_id', 'customer_id'
        )->withPivot('chair_name', 'status');
    }
}
