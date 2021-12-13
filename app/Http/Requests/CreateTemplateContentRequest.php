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
            'name' => 'required',
            'preview_image' => 'required|mimes:jpg,png|max:10000',
            'font_name' => 'required',
            'content' => 'required',
            'status' => 'required|boolean'
        ];
    }
}
