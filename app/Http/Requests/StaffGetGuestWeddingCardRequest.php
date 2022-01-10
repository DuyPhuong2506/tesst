<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;

class StaffGetGuestWeddingCardRequest extends ApiRequest
{
   
    protected $userStaff;

    public function __construct()
    {
        $this->userStaff = Auth::user();
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
                function($attribute, $value, $fail){
                    $guestID = request()->id;
                    $restaurantID = $this->userStaff->restaurant_id;

                    $exists = Restaurant::where('id', $restaurantID)
                        ->whereHas('places', function($q) use($guestID){
                            $q->whereHas('weddings', function($q) use($guestID){
                                $q->whereHas('customers', function($q) use($guestID){
                                    $q->where('id', $guestID);
                                });
                            });
                        })
                        ->exists();

                    if(!$exists){
                        $fail(__('messages.participant.validation.id.exists'));
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            
        ];
    }
}
