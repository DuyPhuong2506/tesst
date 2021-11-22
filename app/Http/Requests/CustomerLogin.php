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
            'username' => 'required|string|exists:customers,username|max:12|min:12|regex:/^[a-zA-Z0-9]+$/',
            'password' => 'required|string|max:12|min:12|regex:/^[a-zA-Z0-9]+$/',
        ];
    }
}
