<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Wedding;
use App\Models\Customer;

class StaffGetGuestRequest extends ApiRequest
{
    private $staffUser;

    public function __construct()
    {
        $this->staffUser = Auth::user();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => [
                'required',
                function($attribute, $value, $fail)
                {
                    $guestID = request()->id;
                    $staffID = $this->staffUser->id;
                    $weddingID = Customer::find($guestID)->wedding_id;

                    $exist = Wedding::where('id', $weddingID)
                        ->whereHas('place', function($q) use($staffID){
                            $q->whereHas('restaurant', function($q) use($staffID){
                                $q->whereHas('user', function($q) use($staffID){
                                    $q->where('id', $staffID);
                                });
                            });
                        })
                        ->exists();

                    if(!$exist){
                        $fail(__('messages.participant.validation.id.exists'));
                    }
                }
            ]
        ];
    }

    public function messages()
    {
        return [
            'id.required' => __('messages.event.validation.id.required')
        ];
    }
}
