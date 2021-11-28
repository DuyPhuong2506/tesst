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
        'full_name'
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
}
