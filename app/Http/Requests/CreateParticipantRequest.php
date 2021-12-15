<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class CreateRestaurantRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'is_only_party' => 'required|boolean',
            'first_name' => 'required',
            'last_name' => 'required',
            'relationship' => 'required',
            'email' => 'required|max:50|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'post_code' => 'required|digit_between:7|numeric',
            'address' => 'required|max:200|string',
            'phone' => 'required|digit_between:10,11',
            
            'customer_relatives' => 'array|min:0',
            'customer_relatives.*.surname' => 'required',
            'customer_relatives.*.name' => 'required',
            'customer_relatives.*.relationship' => 'required',

            'customer_type' => 'required',
            'task_content' => 'required',
            'free_word' => 'required',
            'bank_account_id' => 'required',
            'is_send_wedding_card' => 'required|boolean',
        ];
    }

    // public function messages()
    // {

    // }
}
