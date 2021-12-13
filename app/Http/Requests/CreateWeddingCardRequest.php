<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class CreateTemplateContentRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'card_url' => 'required',
            'content' => 'required',
            'img_url' => 'required',
            'wedding_price' => 'required',
            'bank_account.*.bank_name' => 'required',
            'bank_account.*.bank_branch' => 'required',
            'bank_account.*.account_number' => 'required',
            'bank_account.*.card_type' => 'required',
            'bank_account.*.holder_name' => 'required',
        ];
    }
}
