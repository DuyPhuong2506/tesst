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
            'phone' => 'required|numeric|digits:11',
            'company_name' => 'required|string|max:20',
            'post_code' => 'required|digits:7',
            'address_1' => 'required|max:200',
            'address_2' => 'max:200',
            'guest_invitation_response_num' => 'required|numeric',
            'couple_edit_num' => 'required|numeric',
            'couple_invitation_edit_num' => 'required|numeric',
            'ceremony_confirm_num' => 'required|numeric',
        ];
    }
}
