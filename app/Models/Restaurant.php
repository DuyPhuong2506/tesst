<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = ['name', 'phone', 'address', 'logo_url', 'greeting_msg'];
    protected $table = 'restaurants';
}
