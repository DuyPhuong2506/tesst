<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Carbon\Carbon;
use App\Models\Wedding;

class CreateEventRequest extends ApiRequest
{
    protected $placeID;
    protected $date;

    public function __construct()
    {
        $this->placeID = request()->place_id;
        $this->date = request()->date;
    }
    
    public function isDuplicateTime($fieldTime, $dbTimeType)
    {
        $placeID = $this->placeID;
        $date = $this->date;
        $dbTimeName = [];

        if($dbTimeType === "ceremony"){
            $dbTimeName = ["ceremony_reception_time", "ceremony_time"];
        } else if($dbTimeType === "party"){
            $dbTimeName = ["party_reception_time", "party_time"];
        }

        $wedding = Wedding::whereHas('place', function($q) use($placeID){
            $q->where('id', $placeID);
        })->where('date', $date);

        $listWedding = $wedding->get();

        if($wedding->exists()){
            foreach ($listWedding as $key => $value) 
            {
                $dbTime1 = [null, null];
                if(isset($value[$dbTimeName[0]])){
                    $dbTime1 = explode("-", $value[$dbTimeName[0]]);
                }
                $dbTime2 = explode("-", $value[$dbTimeName[1]]);

                if(
                    isBetween($dbTime1[0], $dbTime1[1], $fieldTime[0], $fieldTime[1]) OR
                    isBetween($dbTime2[0], $dbTime2[1], $fieldTime[0], $fieldTime[1])
                ){
                    return true;
                }
            }
        }

        return false;
    }
    
    public function rules()
    {
        $rule = [
            'title' => 'required|max:100|string',
            'date' => 'required|date_format:Y-m-d|after:today',
            'pic_name' => 'required|string|max:20',

            'ceremony_reception_time' => [
                'nullable',
                'array',
                'min:2',
                'max:2',
                function($attribute, $value, $fail){
                    $ceremonyReceptionTime = request()->ceremony_reception_time;
                    $isDuplicate = $this->isDuplicateTime(
                        $ceremonyReceptionTime, "ceremony"
                    );

                    if(isset($ceremonyReceptionTime)){
                        if($isDuplicate){
                            $fail(__('messages.event.validation.date.was_held'));
                        }
                    }
                }
            ],
            'ceremony_reception_time.*' => "date_format:H:i",
            'ceremony_reception_time.1' => "after:ceremony_reception_time.0",

            'ceremony_time' => [
                'array',
                'required',
                'min:2',
                'max:2',
                function($attribute, $value, $fail){
                    $ceremonyTime = request()->ceremony_time;
                    $isDuplicate = $this->isDuplicateTime(
                        $ceremonyTime, "ceremony"
                    );

                    if(isset($ceremonyTime)){
                        if($isDuplicate){
                            $fail(__('messages.event.validation.date.was_held'));
                        }
                    }
                }
            ],
            'ceremony_time.*' => "date_format:H:i",
            'ceremony_time.0' => "after_or_equal:ceremony_reception_time.1",
            'ceremony_time.1' => "after:ceremony_time.0",

            'party_reception_time' => [
                'nullable',
                'array',
                'min:2',
                'max:2',
                function($attribute, $value, $fail){
                    $partyReceptionTime = request()->party_reception_time;
                    $isDuplicate = $this->isDuplicateTime(
                        $partyReceptionTime, "party"
                    );

                    if(isset($partyReceptionTime)){
                        if($isDuplicate){
                            $fail(__('messages.event.validation.date.was_held'));
                        }
                    }
                }
            ],
            'party_reception_time.*' => "date_format:H:i",
            'party_reception_time.0' => "after_or_equal:ceremony_time.1",
            'party_reception_time.1' => "after:party_reception_time.0",

            'party_time' => [
                'array',
                'required',
                'min:1',
                'max:2',
                function($attribute, $value, $fail){
                    $partyTime = request()->party_time;
                    $isDuplicate = $this->isDuplicateTime(
                        $partyTime, "party"
                    );

                    if(isset($partyTime)){
                        if($isDuplicate){
                            $fail(__('messages.event.validation.date.was_held'));
                        }
                    }
                }
            ],
            'party_time.*' => "date_format:H:i",
            'party_time.0' => "after_or_equal:party_reception_time.1",
            'party_time.1' => "after:party_time.0",

            'place_id' => 'required|exists:places,id,status,1',
            
            'groom_name' => 'required|string|max:20',
            'groom_email' => 'required|max:50|string|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'bride_name' => 'required|string|max:20',
            'bride_email' => 'required|max:50|string|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',

            'allow_remote' => 'required|boolean',
            'guest_invitation_response_date' => 'required|date_format:Y-m-d|before:couple_edit_date',
            'couple_edit_date' => 'required|date_format:Y-m-d|before:date|after:guest_invitation_response_date'
        ];

        if(empty(request()->ceremony_reception_time)){
            $rule['ceremony_time.0'] = "";
        }

        if(empty(request()->party_reception_time)){
            $rule['party_time.0'] = 'required|after_or_equal:ceremony_time.1';
        }
        
        if(count(request()->party_time) == 1){
            $rule['party_time.1'] = "";
        }

        return $rule;
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

            'ceremony_reception_time.array' => __('messages.event.validation.time_line.array'),
            'ceremony_reception_time.min' => __('messages.event.validation.time_line.min'),
            'ceremony_reception_time.max' => __('messages.event.validation.time_line.max'),
            'ceremony_reception_time.*.date_format' => __('messages.event.validation.time_line.date_format'),
            'ceremony_reception_time.1.after' => __('messages.event.validation.time_line.after'),

            'ceremony_time.array' => __('messages.event.validation.time_line.array'),
            'ceremony_time.required' => __('messages.event.validation.time_line.required'),
            'ceremony_time.min' => __('messages.event.validation.time_line.min'),
            'ceremony_time.max' => __('messages.event.validation.time_line.max'),
            'ceremony_time.*.date_format' => __('messages.event.validation.time_line.date_format'),
            'ceremony_time.0.after_or_equal' => __('messages.event.validation.time_line.after_or_equal'),
            'ceremony_time.1.after' => __('messages.event.validation.time_line.after'),

            'party_reception_time.array' => __('messages.event.validation.time_line.array'),
            'party_reception_time.min' => __('messages.event.validation.time_line.min'),
            'party_reception_time.max' => __('messages.event.validation.time_line.max'),
            'party_reception_time.*.date_format' => __('messages.event.validation.time_line.date_format'),
            'party_reception_time.0.after_or_equal' => __('messages.event.validation.time_line.after_or_equal'),
            'party_reception_time.1.after' => __('messages.event.validation.time_line.after'),

            'party_time.array' => __('messages.event.validation.time_line.array'),
            'party_time.required' => __('messages.event.validation.time_line.required'),
            'party_time.min' => __('messages.event.validation.time_line.min'),
            'party_time.max' => __('messages.event.validation.time_line.max'),
            'party_time.*.date_format' => __('messages.event.validation.time_line.date_format'),
            'party_time.0.after_or_equal' => __('messages.event.validation.time_line.after_or_equal'),
            'party_time.1.after' => __('messages.event.validation.time_line.after'),
            
            'place_id.required' => __('messages.event.validation.place.required'),
            'place_id.exists' => __('messages.event.validation.place.exists'),
            
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
            
            'guest_invitation_response_date.required' => __('messages.event.validation.guest_invitation_response_date.required'),
            'guest_invitation_response_date.date_format' => __('messages.event.validation.guest_invitation_response_date.date_format'),
            'guest_invitation_response_date.before' => __('messages.event.validation.guest_invitation_response_date.before'),

            'couple_edit_date.required' => __('messages.event.validation.couple_edit_date.required'),
            'couple_edit_date.date_format' => __('messages.event.validation.couple_edit_date.date_format'),
            'couple_edit_date.before' => __('messages.event.validation.couple_edit_date.before'),
            'couple_edit_date.after' => __('messages.event.validation.couple_edit_date.after'),
        ];
    }
}
