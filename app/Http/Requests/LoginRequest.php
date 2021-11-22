<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

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
            'email' => 'required|string|email|exists:users,email|max:50|regex:/^[a-zA-Z0-9@._-]+$/',
            'password' => 'required|string|min:8|max:255|regex:/^[a-zA-Z0-9]+$/',
        ];
    }
}
