<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class UpdateRestaurantRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'link_place' => 'url',
        ];
    }
    public function messages()
    {
        return [
            'link_place.url' => __('messages.restaurant.validation.url.url'),
        ];
    }
}
