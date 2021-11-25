<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wedding extends Model
{
    protected $fillable = [
        'id',
        'title',
        'date',
        'pic_name',
        'ceremony_reception_time',
        'ceremony_time',
        'party_reception_time',
        'party_time',
        'is_close',
        'place_id',
        'table_map_image',
        'greeting_message',
        'thank_you_message',
        'allow_remote',
        'guest_invitation_response_date',
        'couple_edit_date',
        'couple_invitation_edit_date',
        'ceremony_confirm_date'
    ];
    
    protected $table = 'weddings';

    public function eventTimes()
    {
        return $this->hasMany(EventTimes::class, 'event_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    public function customer()
    {
        return $this->hasMany(Customer::class, 'wedding_id', 'id');
    } 
}
