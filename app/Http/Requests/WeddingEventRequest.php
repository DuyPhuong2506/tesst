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
            'title' => 'required|max:100|string',
            'date' => 'required|date_format:Y-m-d H:i',
            'pic_name' => 'required|string|max:100',
            'ceremony_reception_time' => 'required|regex:/(\d+\-\d+)/',
            'ceremony_time' => 'required|regex:/(\d+\-\d+)/',
            'party_reception_time' => 'required|regex:/(\d+\-\d+)/',
            'party_time' => 'required|regex:/(\d+\-\d+)/',
            'is_close' => 'boolean',
            'place_id' => 'required|exists:places,id',
            'table_map_image' => 'string|max:100',
            'greeting_message' => 'string|max:100',
            'thank_you_message' => 'string|max:500',
            'groom_name' => 'required|string|max:30',
            'groom_email' => 'required|string|email',
            'bride_name' => 'required|string|max:30',
            'bride_email' => 'required|string|email'
        ];
    }
}
