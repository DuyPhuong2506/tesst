<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class CreateWeddingCardRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'template_card_id' => 'required|exists:template_cards,id',
            'content' => 'required',
            'couple_photo' => 'required',
            'wedding_price' => 'required',
            'bank_accounts' => 'array|nullable|min:1|max:2',
            'bank_accounts.0.bank_name' => 'required',
            'bank_accounts.0.bank_branch' => 'required',
            'bank_accounts.0.account_number' => 'required',
            'bank_accounts.0.card_type' => 'required',
            'bank_accounts.0.holder_name' => 'required',
        ];

        $bank_accounts = request()->bank_accounts;
        if(count($bank_accounts) > 1){
            $rules['bank_accounts.1.bank_name'] = 'required';
            $rules['bank_accounts.1.bank_branch'] = 'required';
            $rules['bank_accounts.1.account_number'] = 'required';
            $rules['bank_accounts.1.card_type'] = 'required';
            $rules['bank_accounts.1.holder_name'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'template_card_id.required' => __('messages.wedding_card.validation.template_card_id.required'),
            'template_card_id.exists' => __('messages.wedding_card.validation.template_card_id.exists'),
            'content.required' => __('messages.wedding_card.validation.content.required'),
            'couple_photo.required' => __('messages.wedding_card.validation.couple_photo.required'),
            'wedding_price.required' => __('messages.wedding_card.validation.wedding_price.required'),
            'bank_accounts.*.bank_name.required' =>  __('messages.bank_account.validation.bank_name.required'),
            'bank_accounts.*.bank_branch.required' =>  __('messages.bank_account.validation.bank_branch.required'),
            'bank_accounts.*.account_number.required' =>  __('messages.bank_account.validation.account_number.required'),
            'bank_accounts.*.card_type.required' =>  __('messages.bank_account.validation.card_type.required'),
            'bank_accounts.*.holder_name.required' =>  __('messages.bank_account.validation.holder_name.required'),
        ];
    }
}
