<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class UpdateCardContentRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'content.required' => __('messages.wedding_card.validation.content.required'),
        ];
    }
}
