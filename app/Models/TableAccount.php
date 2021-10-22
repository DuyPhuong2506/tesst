<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableAccount extends Model
{
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
