<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class UpdateThankMsg extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'thank_you_message' => 'string|max:400',
            'event_id' => 'required|exists:weddings,id'
        ];
    }
}
