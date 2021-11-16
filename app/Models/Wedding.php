<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wedding extends Model
{
    protected $fillable = [
        'id',
        'event_name',
        'date',
        'welcome_start',
        'welcome_end',
        'wedding_start',
        'wedding_end',
        'reception_start',
        'reception_end',
        'place_id',
        'groom_name',
        'groom_email',
        'bride_name',
        'bride_email'
    ];
    protected $table = 'weddings';

    public function eventTimes(){
        return $this->hasMany(EventTimes::class, 'event_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }
}
