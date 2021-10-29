<?php

namespace App\Models;

class TableAccount extends AuthJWT
{
    protected $fillable = ['username', 'email','password'];
}
