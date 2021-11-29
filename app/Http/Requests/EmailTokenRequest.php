<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class EmailTokenRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required|exists:users,remember_token|max:400'
        ];
    }

    public function messages()
    {
        return [
            'token.required' => __('messages.user.validation.token.required'),
            'token.exists' => __('messages.user.token_fail')
        ];
    }
}
