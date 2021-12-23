<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class UpdateThankMsgRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'thank_you_message' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'greeting_message.required' => __('messages.event.validation.greeting_message.required')
        ];
    }
}
