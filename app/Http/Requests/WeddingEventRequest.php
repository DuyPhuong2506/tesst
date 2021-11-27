<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Carbon\Carbon;
use App\Models\Wedding;

class WeddingEventRequest extends ApiRequest
{
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
                        $fail(__('messages.event.validation.date.was_held'));
                    }
                }
            ],
            'pic_name' => 'required|string|max:50',

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

    public function messages()
    {
        return [
            'title.required' => __('messages.event.validation.title.required'),
            'title.max' => __('messages.event.validation.title.max'),

            'date.required' => __('messages.event.validation.date.required'),
            'date.date_format' => __('messages.event.validation.date.date_format'),
            'date.after' => __('messages.event.validation.date.after'),

            'pic_name.required' => __('messages.event.validation.pic_name.required'),
            'pic_name.max' => __('messages.event.validation.pic_name.max'),

            'ceremony_reception_time.required' => __('messages.event.validation.time_line.required'),
            'ceremony_reception_time.array' => __('messages.event.validation.time_line.array'),
            'ceremony_reception_time.*.date_format' => __('messages.event.validation.time_line.date_format'),
            'ceremony_reception_time.*.required' => __('messages.event.validation.time_line.required'),
            'ceremony_reception_time.date_format' => __('messages.event.validation.time_line.date_format'),
            'ceremony_reception_time.1.required' => __('messages.event.validation.time_line.required'),
            'ceremony_reception_time.1.after' => __('messages.event.validation.time_line.after'),

            'ceremony_time.required' => __('messages.event.validation.time_line.required'),
            'ceremony_time.array' => __('messages.event.validation.time_line.array'),
            'ceremony_time.*.required' => __('messages.event.validation.time_line.required'),
            'ceremony_time.*.date_format' => __('messages.event.validation.time_line.date_format'),
            'ceremony_time.0.required' => __('messages.event.validation.time_line.required'),
            'ceremony_time.0.after_or_equal' => __('messages.event.validation.time_line.after'),
            'ceremony_time.1.required' => __('messages.event.validation.time_line.required'),
            'ceremony_time.1.after' => __('messages.event.validation.time_line.after'),

            'party_reception_time.required' => __('messages.event.validation.time_line.required'),
            'party_reception_time.array' => __('messages.event.validation.time_line.array'),
            'party_reception_time.*.required' => __('messages.event.validation.time_line.required'),
            'party_reception_time.*.date_format' => __('messages.event.validation.time_line.date_format'),
            'party_reception_time.0.required' => __('messages.event.validation.time_line.required'),
            'party_reception_time.0.after_or_equal' => __('messages.event.validation.time_line.after'),
            'party_reception_time.1.required' => __('messages.event.validation.time_line.required'),
            'party_reception_time.1.after' => __('messages.event.validation.time_line.after'),

            'party_time.required' => __('messages.event.validation.time_line.required'),
            'party_time.array' => __('messages.event.validation.time_line.array'),
            'party_time.*.required' => __('messages.event.validation.time_line.required'),
            'party_time.*.date_format' => __('messages.event.validation.time_line.date_format'),
            'party_time.0.required' => __('messages.event.validation.time_line.required'),
            'party_time.0.after_or_equal' => __('messages.event.validation.time_line.after'),
            'party_time.1.required' => __('messages.event.validation.time_line.required'),
            'party_time.1.after' => __('messages.event.validation.time_line.after'),

            'place_id.required' => __('messages.event.validation.place.required'),
            'place_id.exists' => __('messages.event.validation.place.exists'),

            'is_close.boolean' => __('messages.event.validation.is_close.boolean'),
            'table_map_image.max' => __('messages.event.validation.table_map_image.max'),
            'greeting_message.max' => __('messages.event.validation.greeting_message.max'),
            'thank_you_message.max' => __('messages.event.validation.thank_you_message.max'),
            
            'groom_name.required' => __('messages.event.validation.couple_name.required'),
            'groom_name.max' => __('messages.event.validation.couple_name.max'),
            'bride_name.required' => __('messages.event.validation.couple_name.required'),
            'bride_name.max' => __('messages.event.validation.couple_name.max'),
            
            'groom_email.required' => __('messages.mail.validation.email.required'),
            'groom_email.max' => __('messages.mail.validation.email.max'),
            'groom_email.email' => __('messages.mail.validation.email.regex'),
            'groom_email.regex' => __('messages.mail.validation.email.regex'),
            
            'bride_email.required' => __('messages.mail.validation.email.required'),
            'bride_email.max' => __('messages.mail.validation.email.max'),
            'bride_email.email' => __('messages.mail.validation.email.regex'),
            'bride_email.regex' => __('messages.mail.validation.email.regex'),

            'allow_remote.required' => __('messages.mail.validation.email.required'),
            'allow_remote.boolean' => __('messages.mail.validation.email.boolean'),            
        ];
    }
}
