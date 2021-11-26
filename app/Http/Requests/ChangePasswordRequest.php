<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token'=>'required|exists:users,remember_token',
            'password'=>'required|confirmed|min:8|max:255|regex:/^([A-Za-z0-9\d$!^(){}?\[\]<>~%@#&*+=_-]+)$/'
        ];
    }
}
