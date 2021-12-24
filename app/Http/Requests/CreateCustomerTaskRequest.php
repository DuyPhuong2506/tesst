<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class CreateCustomerTaskRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('messages.customer_task.validation.name.required'),
            'description.required' => __('messages.customer_task.validation.description.required'),
        ];
    }
}
