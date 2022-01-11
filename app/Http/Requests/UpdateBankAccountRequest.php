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
            'wedding_price' => 'required|digits_between:0,6',
            'bank_accounts' => 'array|max:2',
            'bank_accounts.*.bank_name' => 'nullable|max:30',
            'bank_accounts.*.bank_branch' => 'nullable|max:30',
            'bank_accounts.*.account_number' => 'nullable|digits:8',
            'bank_accounts.*.card_type' => 'nullable|max:10',
            'bank_accounts.*.holder_name' => 'nullable|max:20',
        ];
    }

    public function messages()
    {
        return [
            'wedding_price.required' => __('messages.wedding_card.validation.wedding_price.required'),
            'wedding_price.digits_between' => __('messages.wedding_card.validation.wedding_price.digits_between'),
            'wedding_price.max' => __('messages.wedding_card.validation.wedding_price.max'),

            'wedding_price.numeric' => __('messages.wedding_card.validation.wedding_price.numeric'),
            'bank_accounts.array' => __('messages.bank_account.validation.bank_account.required'),
            'bank_accounts.max' => __('messages.bank_account.validation.bank_account.max'),
            
            'bank_accounts.*.bank_name.max' => __('messages.bank_account.validation.bank_name.max'),
            'bank_accounts.*.bank_branch.max' => __('messages.bank_account.validation.bank_branch.max'),
            'bank_accounts.*.account_number.digits' => __('messages.bank_account.validation.account_number.digits'),
            'bank_accounts.*.card_type.max' => __('messages.bank_account.validation.card_type.max'),
            'bank_accounts.*.holder_name.max' => __('messages.bank_account.validation.holder_name.max'),
            
        ];
    }
}
