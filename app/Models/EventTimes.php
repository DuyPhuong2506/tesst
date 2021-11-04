<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTimes extends Model
{
    protected $table = 'wedding_timetable';
    protected $fillable = ['id','start','end','description','event_id'];
}
