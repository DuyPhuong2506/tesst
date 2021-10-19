<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class CreateAdminRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|unique:users',
            'email' => 'required|email',
            'password' => 'required',
            'restaurant_id' => 'required|exists:\App\Models\Restaurant,id'
        ];
    }
}
