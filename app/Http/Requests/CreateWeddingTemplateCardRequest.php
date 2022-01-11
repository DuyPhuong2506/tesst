<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class CreateWeddingTemplateCardRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'type' => 'required',
            'card_path' => 'required|mimes:jpg,png|max:10240'
        ];
    }
}
