<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class UpdateSuperInfoRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'nullable|email',
            'old_password' => 'nullable|min:8|max:16',
            'password' => 'nullable|min:8|max:16|confirmed'
        ];
    }
}
