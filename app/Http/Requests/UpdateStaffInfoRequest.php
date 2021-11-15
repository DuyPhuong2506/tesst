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
            'restaurant_name' => 'required',
            'contact_name' => 'required',
            'contact_email' => 'required|email',
            'phone' => 'required|numeric',
            'company_name' => 'required',
            'post_code' => 'required|numeric',
            'address_1' => 'required',
            'address_2' => 'required'
        ];
    }
}
