<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class WeddingEventRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_name'=>'required|max:100',
            'date'=>'required|date_format:H:i',
            'welcome_start'=>'required|date_format:H:i',
            'welcome_end'=>'required|date_format:H:i',
            'wedding_start'=>'required|date_format:H:i',
            'wedding_end'=>'required|date_format:H:i',
            'reception_start'=>'required|date_format:H:i',
            'reception_end'=>'required|date_format:H:i',
            'place_id'=>'required|exists:places,id',
            'groom_name'=>'required|max:30|min:2',
            'groom_email'=>'email|max:30',
            'bride_name'=>'required|max:30|min:2',
            'bride_email'=>'email|max:30',
        ];
    }
}
