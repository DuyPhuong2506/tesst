<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class EmailRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email,deleted_at,NULL|max:50|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('messages.mail.validation.email.required'),
            'email.regex' => __('messages.mail.validation.email.regex'),
            'email.email' => __('messages.mail.validation.email.regex'),
            'email.exists' => __('messages.user.validation.email.exists'),
            'email.max' => __('messages.mail.validation.email.max'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->respondError(
            Response::HTTP_BAD_REQUEST, 
            __('messages.user.validation.email.exists')
        );

        throw new HttpResponseException($response);
    }
}
