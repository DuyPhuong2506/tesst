<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePlaceRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:200',
            'restaurant_id' => 'required|exists:\App\Models\Restaurant,id',
            'table_positions' => 'array',
            'table_positions.*.amount_chair' => 'required|integer|max:99',
            'table_positions.*.position' => 'required|string|max:20',
        ];
    }
}
