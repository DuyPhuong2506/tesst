<?php

namespace App\Models;

class Customer extends AuthJWT
{
    protected $fillable = ['username', 'email','password'];
}
