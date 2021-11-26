<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Carbon\Carbon;
use App\Models\Wedding;

class WeddingEventRequest extends ApiRequest
{
   

    public $placeID;

    public function rules()
    {

        return [
            'title' => 'required|max:200|string',
            'date' => [
                'required', 
                'date_format:Y-m-d H:i',
                'after:today',
                function($attribute, $value, $fail){
                    $placeId = request()->place_id;
                    $eventDate = Carbon::parse($value)->format('Y-m-d');
                    $exist = Wedding::whereDate('date', '=', $eventDate)
                                    ->whereHas('place', function($q) use($placeId){
                                        $q->where('id', $placeId);
                                    })
                                    ->exists();
                    if($exist){
                        $fail("Opp! Exist event held in this place on $eventDate");
                    }
                }
            ],
            'pic_name' => 'required|string|max:100',

            'ceremony_reception_time' => 'array|required',
            'ceremony_reception_time.*' => "required|date_format:H:i",
            'ceremony_reception_time.1' => "required|after:ceremony_reception_time.0",

            'ceremony_time' => 'array|required',
            'ceremony_time.*' => "required|date_format:H:i",
            'ceremony_time.0' => "required|after_or_equal:ceremony_reception_time.1",
            'ceremony_time.1' => "required|after:ceremony_time.0",

            'party_reception_time' => 'array|required',
            'party_reception_time.*' => "required|date_format:H:i",
            'party_reception_time.0' => "required|after_or_equal:ceremony_time.1",
            'party_reception_time.1' => "required|after:party_reception_time.0",

            'party_time' => 'array|required',
            'party_time.*' => "required|date_format:H:i",
            'party_time.0' => "required|after_or_equal:party_reception_time.1",
            'party_time.1' => "required|after:party_time.0",

            'is_close' => 'boolean',
            'place_id' => 'required|exists:places,id,status,1',
            'table_map_image' => 'string|max:100',
            'greeting_message' => 'string|max:100',
            'thank_you_message' => 'string|max:500',
            'groom_name' => 'required|string|max:100',
            'groom_email' => 'required|max:50|string|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'bride_name' => 'required|string|max:100',
            'bride_email' => 'required|max:50|string|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',

            'allow_remote' => 'required|boolean',
            'guest_invitation_response_date' => 'required|date_format:Y-m-d|before:date',
            'couple_edit_date' => 'required|date_format:Y-m-d|before:date',
            'couple_invitation_edit_date' => 'required|date_format:Y-m-d|before:date',
            'ceremony_confirm_date' => 'required|date_format:Y-m-d|before:date',
        ];
    }
}
