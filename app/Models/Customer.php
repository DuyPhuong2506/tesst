<?php

namespace App\Models;

class Customer extends AuthJWT
{
    protected $fillable = [
        'username', 
        'email', 
        'password', 
        'wedding_id', 
        'role',
        'phone',
        'address',
        'full_name'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $table = 'customers';

    public function wedding()
    {
        return $this->belongsTo(Wedding::class, 'wedding_id');
    }

    public function tablePosition()
    {
        return $this->belongsTo(TablePosition::class, 'table_position_id');
    }
}
