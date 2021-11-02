<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Company extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name',
      'description',
      'is_active'
    ];

}
