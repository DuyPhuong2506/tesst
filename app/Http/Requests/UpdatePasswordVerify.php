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
            'verify_password' => 'required|min:8|max:255',
            'password' => 'required|confirmed|min:8|max:255|regex:/^[a-zA-Z0-9]+$/'
        ];
    }
}
