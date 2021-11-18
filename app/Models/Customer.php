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
    
    protected $table = 'customers';

    public function wedding()
    {
        return $this->belongsTo(Wedding::class, 'wedding_id');
    }
}
