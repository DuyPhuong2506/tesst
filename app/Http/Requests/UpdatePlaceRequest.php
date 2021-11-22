<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlaceRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = request()->id;
        return [
            'name' => 'required|max:200',
            'restaurant_id' => 'required|exists:\App\Models\Restaurant,id',
            'table_positions' => 'array',
            'table_positions.*.amount_chair' => 'required|integer|max:99',
            'table_positions.*.position' => 'required|string|max:20',
            'del_table_positions' => 'array',
            'update_table_positions.*.id' => 'required',
            'update_table_positions.*.amount_chair' => 'required|integer|max:99',
            'update_table_positions.*.position' => 'required|string|max:20',
            'update_table_positions' => 'array',
        ];
    }
}
