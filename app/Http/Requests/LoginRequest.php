<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class LoginRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email|max:50|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'password' => 'required|string|min:8|max:255|regex:/^([A-Za-z0-9\d$!^(){}?\[\]<>~%@#&*+=_-]+)$/',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('messages.user.validation.email.required'),
            'email.regex' => __('messages.user.validation.email.regex'),
            'email.email' => __('messages.user.validation.email.regex'),
            'email.max' => __('messages.mail.validation.email.max'),
            'password.required' => __('messages.user.validation.password.required'),
            'password.min' => __('messages.user.validation.password.min'),
            'password.max' => __('messages.user.validation.password.max'),
            'password.regex' => __('messages.user.validation.password.regex'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->respondError(
            Response::HTTP_BAD_REQUEST, 
            __('messages.login.admin.login_fail')
        );

        throw new HttpResponseException($response);
    }
}
