<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class EventIDRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'=>'required|exists:wedding_test,id'
        ];
    }
}
