<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class UpdateUsersInfoRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:users,id',
            'restaurant_name' => 'required',
            'contact_name' => 'required',
            'contact_email' => 'required|email',
            'phone' => 'required|numeric',
            'company_name' => 'required',
            'post_code' => 'required|numeric',
            'created_at' => 'required|date_format:Y-m-d H:i:s',
            'address_1' => 'required',
            'address_2' => 'required',
            'lasted_login' => 'required|date_format:Y-m-d H:i:s'
        ];
    }
}
