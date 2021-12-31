<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;

class StaffGetListGuestRequest extends ApiRequest
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
                    $restaurantID = $this->staffUser->restaurant_id;
                    $weddingID = request()->id;
                    $place = Restaurant::find($restaurantID)->places()->first();
                    $isWedding = $place->weddings()->where('id', $weddingID)->exists();
                    
                    if(!$isWedding){
                        $fail(__('messages.event.validation.id.exists'));
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
