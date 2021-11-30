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
            'contact_name' => 'required|string|max:20',
            'contact_email' => 'required|string|email|max:50|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'phone' => 'required|numeric|digits_between:10,11',
            'company_name' => 'required|string|max:20',
            'post_code' => 'required|digits:7',
            'address_1' => 'required|string|max:200',
            'address_2' => 'max:200',
            'guest_invitation_response_num' => 'required|numeric|min:1|max:180',
            'couple_edit_num' => 'required|numeric|min:1|max:180'
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
            'phone.digits_between' => __('messages.restaurant.validation.phone.digits_between'),
            'company_name.required' => __('messages.restaurant.validation.company_name.required'),
            'company_name.max' => __('messages.restaurant.validation.company_name.max'),
            'post_code.required' => __('messages.restaurant.validation.post_code.required'),
            'post_code.digits' => __('messages.restaurant.validation.post_code.digits'),
            'address_1.required' => __('messages.restaurant.validation.address.required'),
            'address_1.max' => __('messages.restaurant.validation.address.max'),
            'address_2.max' => __('messages.restaurant.validation.address.max'),
            'guest_invitation_response_num.required' => __('messages.restaurant.validation.guest_invitation_response_num.required'),
            'guest_invitation_response_num.numeric' => __('messages.restaurant.validation.guest_invitation_response_num.numeric'),
            'guest_invitation_response_num.max' => __('messages.restaurant.validation.guest_invitation_response_num.max'),
            'guest_invitation_response_num.min' => __('messages.restaurant.validation.guest_invitation_response_num.min'),
            'couple_edit_num.required' => __('messages.restaurant.validation.couple_edit_num.required'),
            'couple_edit_num.numeric' => __('messages.restaurant.validation.couple_edit_num.numeric'),
            'couple_edit_num.max' => __('messages.restaurant.validation.couple_edit_num.max'),
            'couple_edit_num.min' => __('messages.restaurant.validation.couple_edit_num.min'),
        ];
    }
}
