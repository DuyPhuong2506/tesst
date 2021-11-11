<?php

namespace App\Models;

class Customer extends AuthJWT
{
    protected $fillable = ['username', 'email', 'password', 'wedding_id', 'role'];
    protected $table = 'customers';
}
