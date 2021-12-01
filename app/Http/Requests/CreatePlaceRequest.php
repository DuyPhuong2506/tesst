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
            // 'table_positions.*.amount_chair' => 'required|integer|max:99',
            // 'table_positions.*.position' => 'required|string|max:20',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('messages.place.validation.name.required'),
            'name.max' => __('messages.place.validation.name.max'),
            'restaurant_id.required' => __('messages.place.validation.restaurant_id.required'),
            'restaurant_id.exists' => __('messages.place.validation.restaurant_id.exists'),
            'table_positions.array' => __('messages.place.validation.table_positions.array'),
            'table_positions.*.amount_chair.required' => __('messages.place.validation.table_positions.required'),
            'table_positions.*.amount_chair.integer' => __('messages.place.validation.table_positions.integer'),
            'table_positions.*.amount_chair.max' => __('messages.place.validation.table_positions.max'),
            'table_positions.*.position.required' => __('messages.place.validation.table_positions.required'),
            'table_positions.*.position.max' => __('messages.place.validation.table_positions.max'),
        ];
    }
}
