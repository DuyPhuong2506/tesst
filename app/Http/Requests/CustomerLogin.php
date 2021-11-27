<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class CustomerLogin extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => __('messages.couple.validation.username.required'),
            'password.required' => __('messages.couple.validation.password.required'),
        ];
    }
}
