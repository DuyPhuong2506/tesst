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
            'email' => 'required|email',
            'password' => 'required|min:8|max:255',
        ];
    }
}
