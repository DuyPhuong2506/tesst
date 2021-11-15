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
        'address'
    ];
    protected $table = 'customers';
}
