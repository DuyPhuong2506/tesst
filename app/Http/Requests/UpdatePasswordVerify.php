<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class UpdatePasswordVerify extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'verify_password' => 'required|min:8|max:255|regex:/^([A-Za-z0-9\d$!^(){}?\[\]<>~%@#&*+=_-]+)$/',
            'password' => 'required|min:8|max:255|regex:/^([A-Za-z0-9\d$!^(){}?\[\]<>~%@#&*+=_-]+)$/',
            'password_confirmation' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            'verify_password.required' => __('messages.user.validation.password.required'),
            'verify_password.min' => __('messages.user.validation.password.min'),
            'verify_password.max' => __('messages.user.validation.password.max'),
            'verify_password.regex' => __('messages.user.validation.password.regex'),

            'password.required' => __('messages.user.validation.password.required'),
            'password.min' => __('messages.user.validation.password.min'),
            'password.max' => __('messages.user.validation.password.max'),
            'password.regex' => __('messages.user.validation.password.regex'),

            'password_confirmation.required' => __('messages.user.validation.password.required'),
            'password_confirmation.same' => __('messages.user.validation.password.confirmed'),
        ];
    }
}
