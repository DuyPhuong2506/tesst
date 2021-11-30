<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerChannel extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
   protected $table = 'customer_channel';

   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
     'channel_id',
     'is_host',
     'is_guest',
     'status',
     'customer_id',
   ];
}
