<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Channel extends Model
{
    use SoftDeletes;
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'channels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'wedding_id',
      'name',
      'amount',
      'status',
      'type',
      'start_time',
      'end_time',
      'role'
    ];

    public function wedding()
    {
        return $this->belongsTo(Wedding::class, 'wedding_id', 'id');
    }

    public function tableAccount()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
