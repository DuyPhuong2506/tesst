<?php

namespace App\Models;

class Customer extends AuthJWT
{
    protected $fillable = ['username', 'email','password'];

     /**
     * Password need to be all time encrypted.
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = \Hash::make($password);
    }
}
