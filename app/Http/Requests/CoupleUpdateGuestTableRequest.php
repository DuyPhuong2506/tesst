<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\TablePosition;
use App\Constants\Role;
use App\Constants\ResponseCardStatus;
use App\Constants\Common;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;


class CoupleUpdateGuestTableRequest extends ApiRequest
{
    private $coupleCustomer;

    public function __construct()
    {
        $this->coupleCustomer = Auth::guard('customer')->user();
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
                    $weddingID = $this->coupleCustomer->wedding_id;
                    $tableID = request()->table_position_id;

                    $guest = Customer::where('id', $guestID)
                        ->where('role', Role::GUEST);

                    if($guest->exists())
                    {
                        $joinStatus = $guest->select('join_status')
                            ->first()->join_status;
                        
                        if($joinStatus == ResponseCardStatus::REMOTE_JOIN){
                            $amoutGuest = TablePosition::find($tableID)
                                ->customers()
                                ->where('join_status', ResponseCardStatus::REMOTE_JOIN)
                                ->count();
                                
                            if($amoutGuest >= Common::MAX_ONLINE_TABLE){
                                $fail(__('messages.participant.max_remote'));
                            }
                        }
                    }
                }
            ]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->respondError(
            Response::HTTP_BAD_REQUEST, 
            __('messages.participant.max_remote')
        );

        throw new HttpResponseException($response);
    }
}
