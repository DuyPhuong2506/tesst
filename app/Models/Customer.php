<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends AuthJWT
{
    use SoftDeletes;
    
    protected $fillable = [
        'username', 
        'email', 
        'password', 
        'wedding_id', 
        'role',
        'phone',
        'address',
        'full_name',
        'token',
        'invitation_url',
        'table_position_id',
        'join_status',
        'confirmed_at'
    ];

    protected $hidden = [
        'password', 'remember_token', 'deleted_at'
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

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value;
    }

    public function customerInfo()
    {
        return $this->hasOne(CustomerInfo::class, 'customer_id', 'id');
    }

    public function customerRelatives()
    {
        return $this->hasMany(CustomerRelative::class, 'customer_id', 'id');
    }
}
