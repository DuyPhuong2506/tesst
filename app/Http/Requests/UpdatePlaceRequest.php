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
            'name' => 'required',
            'restaurant_id' => 'required|exists:\App\Models\Restaurant,id',
            'image' => 'required|string|max:16300',
            'table_positions.*.amount_chair' => 'required|integer|max:1000',
            'table_positions.*.position' => 'required|string|max:255',
        ];
    }
}
