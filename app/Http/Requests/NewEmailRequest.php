<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class NewEmailRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|min:4|max:50|unique:users,email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('messages.user.validation.email.required'),
            'email.email' => __('messages.user.validation.email.regex'),
            'email.regex' => __('messages.user.validation.email.regex'),
            'email.min' => __('messages.user.validation.email.min'),
            'email.max' => __('messages.user.validation.email.max'),
            'email.unique' => __('messages.user.validation.email.unique'),
        ];
    }
}
