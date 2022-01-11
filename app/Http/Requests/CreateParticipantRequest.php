<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\WeddingCard;

class CreateParticipantRequest extends ApiRequest
{
    private $customer;
    
    public function __construct()
    {
        $this->customer = Auth::guard('customer')->user();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'is_only_party' => 'required|boolean',
            'first_name' => 'required|max:10',
            'last_name' => 'required|max:10',
            'relationship_couple' => 'required|max:50',
            'email' => 'nullable|max:50|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'post_code' => 'required|digits:7|numeric',
            'address' => 'required|max:200|string',
            'phone' => 'required|digits_between:10,11',
            
            'customer_relatives' => 'array|min:0',
            'customer_relatives.*.first_name' => 'nullable|max:10',
            'customer_relatives.*.last_name' => 'nullable|max:10',
            'customer_relatives.*.relationship' => 'nullable|max:50',
            
            'customer_type' => 'required|numeric',
            'task_content' => 'nullable|string|max:400',
            'free_word' => 'nullable|string|max:100',

            'bank_order' => [
                'required',
                'min:0',
                'max:2',
                'numeric',
                function($attribute, $value, $fail){
                    try {
                        $weddingId = $this->customer->wedding_id;
                        $bankOrderRequest = request()->bank_order;

                        $bankOrders = [0];
                        $weddingCard = WeddingCard::where('wedding_id', $weddingId)->firstOrFail();
                        $bankOrderDB = $weddingCard->bankAccounts()
                            ->select('bank_order')
                            ->get();
                                                
                        if(count($bankOrders) > 0){
                            for($i = 0; $i < count($bankOrderDB); $i++){
                                $bankOrders[$i+1] = $bankOrderDB[$i]['bank_order'];
                            }
                        }
                        
                        if(!in_array($bankOrderRequest, $bankOrders)){
                            $fail(__('messages.bank_account.not_exist'));
                        }
                    } catch (\Throwable $th) {
                        $fail(__('messages.bank_account.not_exist'));
                    }                    
                }
            ],
            'is_send_wedding_card' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'is_only_party.required' => __('messages.participant.validation.is_only_party.required'),
            'is_only_party.boolean' => __('messages.participant.validation.is_only_party.boolean'),
            
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

            'customer_type.required' => __('messages.participant.validation.customer_type.required'),
            'customer_type.numeric' => __('messages.participant.validation.customer_type.numeric'),
            
            'task_content.max' => __('messages.participant.validation.task_content.max'),

            'free_word.max' => __('messages.participant.validation.free_word.max'),
            
            'bank_order.required' => __('messages.participant.validation.bank_order.required'),
            'bank_order.min' => __('messages.participant.validation.bank_order.min'),
            'bank_order.max' => __('messages.participant.validation.bank_order.max'),
            'bank_order.numeric' => __('messages.participant.validation.bank_order.numeric'),
            
            'is_send_wedding_card.required' => __('messages.participant.validation.is_send_wedding_card.required'),
            'is_send_wedding_card.boolean' => __('messages.participant.validation.is_send_wedding_card.boolean'),
            
            'customer_relatives.*.first_name.max' => __('messages.participant.validation.customer_relatives.first_name.max'),
            'customer_relatives.*.last_name.max' => __('messages.participant.validation.customer_relatives.last_name.max'),
            'customer_relatives.*.relationship.max' => __('messages.participant.validation.customer_relatives.relationship.max'),
        ];
    }
}
