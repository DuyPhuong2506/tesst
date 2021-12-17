<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTask extends Model
{
    protected $table = 'customer_tasks';
    protected $fillable = ['id', 'name', 'description'];
}
