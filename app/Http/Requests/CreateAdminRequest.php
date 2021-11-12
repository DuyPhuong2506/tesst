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
            'username' => 'unique:users',
            'email' => 'unique:users|email|max:50',
            'password' => 'min:8|max:255',
            'restaurant_id' => 'exists:\App\Models\Restaurant,id'
        ];
    }
}
