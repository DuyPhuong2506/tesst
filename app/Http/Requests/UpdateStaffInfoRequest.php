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
            'address_1' => 'required|string|max:200',
            'address_2' => 'max:200',
            'guest_invitation_response_num' => 'required|numeric',
            'couple_edit_num' => 'required|numeric',
            'couple_invitation_edit_num' => 'required|numeric',
            'ceremony_confirm_num' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'restaurant_name.required' => __('messages.restaurant.validation.restaurant_name.required'),
            'restaurant_name.max' => __('messages.restaurant.validation.restaurant_name.max'),
            'contact_name.required' => __('messages.restaurant.validation.contact_name.required'),
            'contact_name.max' => __('messages.restaurant.validation.contact_name.max'),
            'contact_email.required' => __('messages.mail.validation.email.required'),
            'contact_email.email' => __('messages.mail.validation.email.regex'),
            'contact_email.regex' => __('messages.mail.validation.email.regex'),
            'contact_email.max' => __('messages.mail.validation.email.max'),
            'phone.required' => __('messages.restaurant.validation.phone.required'),
            'phone.numeric' => __('messages.restaurant.validation.phone.numeric'),
            'phone.digits' => __('messages.restaurant.validation.phone.digits'),
            'company_name.required' => __('messages.restaurant.validation.company_name.required'),
            'company_name.max' => __('messages.restaurant.validation.company_name.max'),
            'post_code.required' => __('messages.restaurant.validation.post_code.required'),
            'post_code.digits' => __('messages.restaurant.validation.post_code.digits'),
            'address_1.required' => __('messages.restaurant.validation.address.required'),
            'address_1.max' => __('messages.restaurant.validation.address.max'),
            'address_2.max' => __('messages.restaurant.validation.address.max'),
        ];
    }
}
