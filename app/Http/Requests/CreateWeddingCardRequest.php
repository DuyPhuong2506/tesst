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
        return [
            'template_card_id' => 'required|exists:template_cards,id',
            'couple_photo' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'template_card_id.required' => __('messages.wedding_card.validation.template_card_id.required'),
            'template_card_id.exists' => __('messages.wedding_card.validation.template_card_id.exists'),
        ];
    }
}
