<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Wedding;

class StaffReoderGuestRequest extends ApiRequest
{

    protected $staffUser;

    public function __construct()
    {
        $this->staffUser = Auth::user();
    }

    public function rules()
    {
        return [
            'wedding_id' => [
                'required',
                function($attribute, $value, $fail){
                    $weddingID = request()->wedding_id;
                    $staffID = $this->staffUser->id;
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
            ],
            'id' => [
                'required',
                function($attribute, $value, $fail){
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
