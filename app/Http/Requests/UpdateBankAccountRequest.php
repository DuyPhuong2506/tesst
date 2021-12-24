<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class UpdateBankAccountRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'wedding_price' => 'required|numeric',
            'bank_accounts' => 'array|max:2'
        ];
    }

    public function messages()
    {
        return [
            'wedding_price.required' => __('messages.wedding_card.validation.wedding_price.required'),
            'wedding_price.numeric' => __('messages.wedding_card.validation.wedding_price.numeric'),
            'bank_accounts.array' => __('messages.bank_account.validation.bank_account.required'),
            'bank_accounts.max' => __('messages.bank_account.validation.bank_account.max'),
        ];
    }
}
