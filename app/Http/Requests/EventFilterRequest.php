<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class EventFilterRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'keyword' => 'string|max:100',
            'order_event_date' => 'string|max:1',
            'order_created_at' => 'string|max:1'
        ];
    }
}
