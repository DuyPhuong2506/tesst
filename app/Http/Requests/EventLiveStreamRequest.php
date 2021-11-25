<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class EventLiveStreamRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_id' => 'required|exists:weddings,id'
        ];
    }
}
