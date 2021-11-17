<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class CreateTimeTableEvent extends ApiRequest
{
    public function rules()
    {
        return [
            'event_id' => 'required|exists:weddings,id',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i',
            'description' => 'required|max:100|string'
        ];
    }
}
