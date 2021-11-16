<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class UpdateEventRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'=>'required|exists:weddings,id',
            'event_name'=>'required|max:100|string',
            'date'=>'required|date_format:Y-m-d H:i',
            'welcome_start'=>'required|date_format:H:i',
            'welcome_end'=>'required|date_format:H:i',
            'wedding_start'=>'required|date_format:H:i',
            'wedding_end'=>'required|date_format:H:i',
            'reception_start'=>'required|date_format:H:i',
            'reception_end'=>'required|date_format:H:i',
            'place_id'=>'required|exists:places,id',
            'groom_name'=>'required|max:30|min:2|string',
            'groom_email'=>'email|max:30|string',
            'bride_name'=>'required|max:30|min:2|string',
            'bride_email'=>'email|max:30|string',
        ];
    }
}
