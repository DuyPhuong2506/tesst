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
            'thank_you_message' => 'nullable|max:200',
        ];
    }

    public function messages()
    {
        return [
            'thank_you_message.max' => __('messages.event.validation.thank_you_message.max')
        ];
    }
}
