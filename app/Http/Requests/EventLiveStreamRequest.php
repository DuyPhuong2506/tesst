<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class EventLiveStreamRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required|exists:customers,token'
        ];
    }

    public function messages()
    {
        return [
            'token.required' => __('messages.event.validation.token.required'),
            'token.exists' => __('messages.event.validation.token.exists'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->respondError(
            Response::HTTP_BAD_REQUEST, 
            __('messages.event.validation.token.exists')
        );

        throw new HttpResponseException($response);
    }
}
