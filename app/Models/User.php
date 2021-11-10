<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use App\Constants\Role;

class User extends AuthJWT
{
    protected $fillable = [
        'username', 
        'email', 
        'restaurant_id',
        'role',
        'company_id',
        'is_first_login',
        'email_at',
        'lasted_login'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];
    
     /**
     * Password need to be all time encrypted.
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = \Hash::make($password);
    }

    public function scopeStaff($query)
    {
        return $query->where(['role' => Role::STAFF_ADMIN]);
    }

    public function isSuperAdmin()
    {
        return $this->role == Role::SUPER_ADMIN;
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
}
