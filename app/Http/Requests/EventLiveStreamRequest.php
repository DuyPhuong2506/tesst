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
            'token' => 'required|exists:customers,token'
        ];
    }

    public function messages()
    {
        return [
            'token.required' => __('messages.event.validation.token.required'),
            'token.exists' => __('messages.event.validation.token.exists'),
        ];
    }
}
