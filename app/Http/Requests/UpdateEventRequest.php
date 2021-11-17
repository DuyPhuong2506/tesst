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
            'thank_you_message' => 'string|max:400',
            'event_times.*.start' => 'required|date_format:H:i',
            'event_times.*.end' => 'required|date_format:H:i',
            'event_times.*.description' => 'required|string|max:200'
        ];
    }
}
