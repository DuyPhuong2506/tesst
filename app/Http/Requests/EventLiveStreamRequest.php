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
            'invitation_url' => 'required|exists:customers,invitation_url'
        ];
    }

    public function messages()
    {
        return [
            'invitation_url.required' => __('messages.event.validation.invitation_url.required'),
            'invitation_url.exists' => __('messages.event.validation.invitation_url.exists'),
        ];
    }
}
