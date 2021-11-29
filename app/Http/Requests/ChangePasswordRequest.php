<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token'=>'required|exists:users,remember_token',
            'password'=>'required|confirmed|min:8|max:255|regex:/^([A-Za-z0-9\d$!^(){}?\[\]<>~%@#&*+=_-]+)$/'
        ];
    }

    public function messages()
    {
        return [
            'token.required' => __('messages.user.validation.token.required'),
            'token.exists' => __('messages.user.validation.token.exists'),
            'password.required' => __('messages.user.validation.password.required'),
            'password.confirmed' => __('messages.user.validation.password.required'),
            'password.min' => __('messages.user.validation.password.min'),
            'password.max' => __('messages.user.validation.password.min'),
            'password.regex' => __('messages.user.validation.password.regex'),
        ];
    }
}
