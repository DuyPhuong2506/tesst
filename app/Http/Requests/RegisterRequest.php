<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class RegisterRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ];
    }
}
