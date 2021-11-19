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
            'email' => 'required|email|min:4|max:50|unique:users,email'
        ];
    }
}
