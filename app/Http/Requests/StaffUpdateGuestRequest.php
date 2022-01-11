<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Constants\Role;
use App\Constants\CustomerConstant;
use App\Constants\Common;
use App\Models\Customer;
use App\Models\Wedding;
use App\Models\TablePosition;
use Carbon\Carbon;

class StaffUpdateGuestRequest extends ApiRequest
{

    protected $staffUser;
    protected $guestUser;

    public function __construct()
    {
        $this->staffUser = Auth::user();
        $this->guestUser = Customer::find(request()->id);
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
                    $staffID = $this->staffUser->id;
                    $weddingID = $this->guestUser->wedding_id;

                    $exist = Wedding::where('id', $weddingID)
                        ->whereHas('place.restaurant.user', function($q) use($staffID){
                            $q->where('id', $staffID);
                        })
                        ->exists();

                    if(!$exist){
                        $fail(__('messages.participant.validation.id.exists'));
                    }
                }
            ],
            'join_status' => 'required|numeric',
            'first_name' => 'required|max:10',
            'last_name' => 'required|max:10',
            'relationship_couple' => 'required|max:50',
            'email' => 'nullable|max:50|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'post_code' => 'required|digits:7|numeric',
            'phone' => 'required|digits_between:10,11',
            'address' => 'required|max:200|string',
            'table_position_id' => [
                function($attribute, $value, $fail)
                {
                    $guestID = request()->id;
                    $tableID = request()->table_position_id;
                    $guest = Customer::where('id', $guestID)
                        ->where('role', Role::GUEST);

                    if($guest->exists())
                    {
                        $weddingID = $this->guestUser->wedding_id;
                        $joinStatus = $guest->select('join_status')
                            ->first()->join_status;
                        
                        if($joinStatus == CustomerConstant::REMOTE_JOIN){
                            $amoutGuest = TablePosition::find($tableID)
                                ->customers()
                                ->where('join_status', CustomerConstant::REMOTE_JOIN)
                                ->count();
                                
                            if($amoutGuest >= Common::MAX_ONLINE_TABLE){
                                $fail(__('messages.participant.max_remote'));
                            }
                        }
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => __('messages.participant.validation.id.required'),
            
            'join_status.required' => __('messages.participant.validation.join_status.required'),
            'join_status.numeric' => __('messages.participant.validation.join_status.numeric'),
        
            'first_name.required' => __('messages.participant.validation.first_name.required'),
            'first_name.max' => __('messages.participant.validation.first_name.max'),

            'last_name.required' => __('messages.participant.validation.last_name.required'),
            'last_name.max' => __('messages.participant.validation.last_name.max'),

            'relationship_couple.required' => __('messages.participant.validation.relationship_couple.required'),
            'relationship_couple.max' => __('messages.participant.validation.relationship_couple.max'),

            'email.max' => __('messages.participant.validation.email.max'),
            'email.regex' => __('messages.participant.validation.email.regex'),
            'email.email' => __('messages.participant.validation.email.regex'),

            'post_code.required' => __('messages.participant.validation.post_code.required'),
            'post_code.digits' => __('messages.participant.validation.post_code.digits'),
            'post_code.numeric' => __('messages.participant.validation.post_code.numeric'),

            'address.required' => __('messages.participant.validation.address.required'),
            'address.max' => __('messages.participant.validation.address.max'),

            'phone.required' => __('messages.participant.validation.phone.required'),
            'phone.digits_between' => __('messages.participant.validation.phone.digits_between'),
        
            'is_only_party.required' => __('messages.participant.validation.is_only_party.required'),
            'is_only_party.boolean' => __('messages.participant.validation.is_only_party.boolean'),
        ];
    }
}
