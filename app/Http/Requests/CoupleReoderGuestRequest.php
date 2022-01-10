<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use App\Constants\Role;
use App\Models\Customer;

class CoupleReoderGuestRequest extends ApiRequest
{

    protected $coupleCustomer;

    public function __construct()
    {
        $this->coupleCustomer = Auth::guard('customer')->user();
    }

    public function rules()
    {
        return [
            'id' => [
                'required',
                'exists:customers,id',
                function($attribute, $value, $fail){
                    $weddingID = $this->coupleCustomer->wedding_id;
                    $guestID = request()->id;
                    $exists = Customer::where('wedding_id', $weddingID)
                        ->where('id', $guestID)
                        ->exists();
                        
                    if(!$exists){
                        $fail(__('messages.participant.validation.id.exists'));
                    }
                }
            ],
            'updated_position' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => __('messages.participant.validation.id.required'),
            'id.exists' => __('messages.participant.validation.id.exists'),

            'updated_position.required' => __('messages.participant.validation.updated_position.required'),
            'updated_position.numeric' => __('messages.participant.validation.updated_position.numeric'),
        ];
    }
}
