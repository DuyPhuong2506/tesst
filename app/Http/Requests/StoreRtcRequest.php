<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class StoreRtcRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $roles = \App\Libs\Agora\RtcTokenBuilder::RolePublisher.', '. \App\Libs\Agora\RtcTokenBuilder::RoleSubscriber;
        $rule = [
            'name' => 'required|max:100|string',
            'role' => 'required|in:'.$roles,
        ];

        return $rule;
    }
}
