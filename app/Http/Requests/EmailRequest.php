<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

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
            'email' => 'required|email|exists:users,email|max:50|regex:/^[a-zA-Z0-9@.]+$/'
        ];
    }
}
