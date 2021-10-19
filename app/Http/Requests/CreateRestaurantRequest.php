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
            'name' => 'required|unique:restaurants',
            'phone' => 'required',
            'address' => 'required',
        ];
    }
}
