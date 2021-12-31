<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;
use App\Models\Wedding;

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
                    $places = Restaurant::find($restaurantID)->places()->select('id')->get();
                    $placeIDs = [];
                    foreach ($places as $key => $value) {
                        array_push($placeIDs, $value['id']);
                    }

                    $exist = Wedding::where('id', $weddingID)
                        ->whereIn('place_id', $placeIDs)
                        ->exists();

                    if(!$exist){
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
