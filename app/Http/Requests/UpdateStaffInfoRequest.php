<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class UpdateStaffInfoRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'restaurant_name' => 'required|string|max:50',
            'contact_name' => 'required|string|max:50',
            'contact_email' => 'required|string|email|max:50|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'phone' => 'required|numeric|min:11',
            'company_name' => 'required|string|max:50',
            'post_code' => 'required|numeric|max:7',
            'address_1' => 'required|max:200',
            'address_2' => 'required|max:200'
        ];
    }
}
