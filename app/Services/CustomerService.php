<?php
namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Repositories\WeddingCardRepository;
use App\Repositories\EventRepository;
use App\Repositories\BankAccountRepository;
use Illuminate\Support\Facades\Auth;
use App\Constants\Common;
use App\Constants\Role;
use App\Constants\InviteSend;
use App\Constants\ResponseCardStatus;
use Str;

class CustomerService
{
    protected $customerRepo;
    protected $weddingCardRepo;
    protected $weddingRepo;
    protected $bankAccountRepo;

    public function __construct(
        CustomerRepository $customerRepo,
        WeddingCardRepository $weddingCardRepo,
        EventRepository $weddingRepo,
        BankAccountRepository $bankAccountRepo
    ){
        $this->customerRepo = $customerRepo;
        $this->weddingCardRepo = $weddingCardRepo;
        $this->weddingRepo = $weddingRepo;
        $this->bankAccountRepo = $bankAccountRepo;
    }

    public function getListCustomerInWedding(array $data)
    {
        $orderBy = isset($data['order_by']) ? explode('|', $data['order_by']) : [];
        $keyword = !empty($data['keyword']) ? escape_like($data['keyword']) : null;
        $paginate = !empty($data['paginate']) ? $data['paginate'] : Common::PAGINATE;
        $auth = Auth::guard('customer')->user();
        $tablePositionId = $data['table_position_id'] ?? null; 
        $roleWeddingIds = [Role::GUEST, Role::BRIDE, Role::GROOM];
        $roleTableIds = [Role::STAGE_TABLE, Role::COUPE_TABLE, Role::SPEECH_TABLE, Role::NORMAL_TABLE];
        $guestPositionTableId = Auth::guard('customer')->user()->tablePosition->first()->id ?? null;
        $getList = $this->customerRepo->model
            ->with(['customerRelative' => function($q) {
                $q->select('id', 'first_name', 'last_name', 'relationship', 'customer_id');
            }])
            ->when(isset($auth->role) && in_array($auth->role, $roleWeddingIds), function($q) use ($auth) {
                $q->whereHas('wedding', function($q) use ($auth){
                    $q->whereId($auth->wedding_id)
                        ->where(function($q) {
                            $q->where('is_close', Common::STATUS_FALSE)
                                ->orWhere('is_close', null);
                        });
                        
                });
            })
            // ->when(isset($auth->role) && in_array($auth->role, $roleTableIds), function($q) use ($auth) {
            //     $q->whereHas('wedding', function($q) use ($auth){
            //         $q->where('place_id', $auth->place_id)
            //             ->where('is_close', Common::STATUS_FALSE);
            //     });
            // });
            ->when(isset($data['in_table']) && isset($guestPositionTableId),function($q) use ($guestPositionTableId) {
                $q->whereHas('tablePosition', function($q) use ($guestPositionTableId){
                    $q->whereId($guestPositionTableId);
                })
                ->whereRole(Role::GUEST);
            })
            ->when(!empty($tablePositionId), function($q) use ($tablePositionId) {
                $q->whereHas('tablePosition', function($q) use ($tablePositionId){
                    $q->whereId($tablePositionId);
                });
            });
        

        if($paginate != Common::PAGINATE_ALL){
            $getList = $getList->paginate($paginate);
        } else {
            if(isset($data['in_table']) && $guestPositionTableId == null){
                $getList = collect([]);
            } else {
                $getList = $getList->get();
            }
        }

        return $getList;
    }

    /**
     * UI COUPLE EVENT DETAIL - LIST GUEST - [U063]
     * @param $weddingID, $request
     * **/
    public function staffCoupleGetListGuest($weddingID, $request)
    {
        $keyword = (isset($request['keyword'])) ? escape_like($request['keyword']) : NULL;
        $status = [];
        
        if(!empty($keyword)){
            $status = getArrayIndex($keyword, ResponseCardStatus::RESPONSE_CARD_STATUS);
        }

        $customerParticipant =  $this->customerRepo->model
            ->where(function($q) use($weddingID, $keyword){
                $q->where('wedding_id', $weddingID);
                $q->where('role', Role::GUEST);
            })
            ->where(function($q) use($keyword, $status){
                $q->orWhere('full_name', 'like', '%'.$keyword.'%');
                $q->orWhere('email', 'like', '%'.$keyword.'%');
                $q->orWhere(function($q) use($keyword){
                    $q->whereHas('tablePosition', function($q) use($keyword){
                        $q->where('position', 'like', '%'.$keyword.'%');
                    });
                });
                $q->orWhere(function($q) use($keyword){
                    $q->whereHas('customerInfo', function($q) use($keyword){
                        $q->where('relationship_couple', 'like', '%'.$keyword.'%');
                    });
                });
                $q->orWhereIn('join_status', $status);
            })
            ->select(['id', 'full_name', 'email', 'join_status'])
            ->with(['tablePosition' => function($q){
                $q->select(['id', 'position']);
            }])
            ->with(['customerInfo' => function($q){
                $q->select('id', 'relationship_couple', 'customer_id');
            }])
            ->get();

        $wedding = $this->weddingRepo->model->where('id', $weddingID)
            ->select('guest_invitation_response_date', 'couple_edit_date', 'is_notify_planner')
            ->first();

        $tableList = $this->weddingRepo->model->find($weddingID)
            ->place()->first()
            ->tablePositions()->get();

        return [
            'guest_invitation_response_date' => $wedding->guest_invitation_response_date,
            'couple_edit_date' => $wedding->couple_edit_date,
            'is_notify_planner' => $wedding->is_notify_planner,
            'customer_participant' => $customerParticipant,
            'table_list' => $tableList
        ];
    }

    public function getBankID(int $bankOrder, int $weddingId)
    {
        $bankAccountId = null;
        if($bankOrder != 0){
            $weddingCard = $this->weddingCardRepo
                ->model
                ->where('wedding_id', $weddingId)
                ->whereHas('bankAccounts', function($q) use($bankOrder){
                    $q->where('bank_order', $bankOrder);
                })
                ->first();
        
            $bankAccount = $weddingCard->bankAccounts()
                ->where('wedding_card_id', $weddingCard->id)
                ->where('bank_order', $bankOrder)
                ->first();

            $bankAccountId = $bankAccount->id;
        }
        
        return $bankAccountId;
    }

    public function transmissionStatus($isSendWeddingCard, $email)
    {
        $emailStatus = InviteSend::UNSEND;
        if($isSendWeddingCard && !isset($email)){
            $emailStatus = InviteSend::NOT_EMAIL;
        }else if(!$isSendWeddingCard){
            $emailStatus = InviteSend::DO_NOT_SEND;
        }

        return $emailStatus;
    }

    public function createParticipant($requestData, $weddingId)
    {
        $customerRelative = null;
        $emailStatus = $this->transmissionStatus(
            $requestData['is_send_wedding_card'],
            $requestData['email'],
        );
        $username = random_str_az(8) . random_str_number(4);
        $password = random_str_az(8) . random_str_number(4);
        $fullname = $requestData['first_name'] . " " . $requestData['last_name'];
        $bankID = $this->getBankID($requestData['bank_order'], $weddingId);

        $customer = $this->customerRepo->create([
            'username' => $username,
            'password' => $password,
            'email' => Str::lower($requestData['email']),
            'role' => Role::GUEST,
            'full_name' => $fullname,
            'wedding_id' => $weddingId
        ]);

        $customerInfo = $customer->customerInfo()->create([
            'is_only_party' => $requestData['is_only_party'],
            'first_name' => $requestData['first_name'],
            'last_name' => $requestData['last_name'],
            'relationship_couple' => $requestData['relationship_couple'],
            'post_code' => $requestData['post_code'],
            'address' => $requestData['address'],
            'phone' => $requestData['phone'],
            'free_word' => $requestData['free_word'],
            'task_content' => $requestData['task_content'],
            'is_send_wedding_card' => $requestData['is_send_wedding_card'],
            'customer_type' => $requestData['customer_type'],
            'bank_account_id' => $bankID,
            'email_status' => $emailStatus
        ]);
        
        if(isset($requestData['customer_relatives'])){
            $customerRelative = $customer->customerRelatives()
                ->createMany($requestData['customer_relatives']);
        }

        return [
            'customer' => $customer,
            'customer_info' => $customerInfo,
            'customer_relative' => $customerRelative
        ];
    }

    public function getListParticipant($data, $weddingId)
    {
        $paginate = !empty($data['paginate']) ? $data['paginate'] : Common::PAGINATE;

        $participants = $this->customerRepo
            ->model
            ->where('wedding_id', $weddingId)
            ->where('role', Role::GUEST)
            ->with(['customerInfo' => function($q){
                $q->select(
                    'id', 'first_name', 'last_name', 'is_send_wedding_card',
                    'is_only_party', 'customer_id', 'email_status',
                );
            }])
            ->select('id', 'full_name', 'email')
            ->withCount('customerRelatives')
            ->paginate($paginate);

        $wedding = $this->weddingRepo
            ->model
            ->where('id', $weddingId)
            ->select('guest_invitation_response_date', 'couple_edit_date', 'is_notify_planner')
            ->first();

        return [
            'participants' => $participants,
            'guest_invitation_response_date' => $wedding->guest_invitation_response_date,
            'couple_edit_date' => $wedding->couple_edit_date,
            'is_notify_planner' => $wedding->is_notify_planner,
        ];
        
    }

    public function deleteParticipant($id, $weddingId)
    {
        $participant = $this->customerRepo
            ->model
            ->where('id', $id)
            ->where('role', Role::GUEST)
            ->where('wedding_id', $weddingId)
            ->first();

        if($participant){
            $participant->delete();
            return true;
        }

        return false;
    }

    public function updateParticipantInfo($data, $weddingId)
    {
        $customer = $this->customerRepo->model->find($data['id']);
        $bankId = $this->getBankID($data['bank_order'], $weddingId);
        $emailStatus = $this->transmissionStatus(
            $data['is_send_wedding_card'],
            $data['email'],
        );

        $customer->update([
            'email' => Str::lower($data['email']),
            'full_name' => $data['first_name'] . " " . $data['last_name'],
        ]);

        $customer->customerInfo()->updateOrCreate(
            ['customer_id' => $customer->id],
            [
                'is_only_party' => $data['is_only_party'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'relationship_couple' => $data['relationship_couple'],
                'post_code' => $data['post_code'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'customer_type' => $data['customer_type'],
                'task_content' => $data['task_content'],
                'free_word' => $data['free_word'],
                'is_send_wedding_card' => $data['is_send_wedding_card'],
                'email_status' => $emailStatus,
                'bank_account_id' => $bankId
            ]
        );

        $customer->customerRelatives()->delete();
        $customer->customerRelatives()->createMany($data['customer_relatives']);

        return [
            'customer' => $customer,
            'customer_info' => $customer->customerInfo()->first(),
            'customer_relatives' => $customer->customerRelatives()->get()
        ];
    }
    
    public function detailParticipant($id, $weddingId, $customerId)
    {
        $participant = $this->customerRepo
            ->model
            ->where('id', $id)
            ->where('wedding_id', $weddingId)
            ->where('role', Role::GUEST)
            ->select('id', 'email', 'wedding_id', 'join_status')
            ->first();

        $participantInfo = $participant->customerInfo()
            ->select(
                'id', 'is_only_party', 'first_name', 'last_name', 'relationship_couple',
                'phone', 'post_code', 'address', 'customer_type', 'task_content',
                'free_word', 'is_send_wedding_card', 'bank_account_id'
            )
            ->first();

        $bankAccount = $this->bankAccountRepo->model
            ->find($participantInfo->bank_account_id);

        if(isset($bankAccount)){
            $participantInfo['bank_order'] = $bankAccount->bank_order;
        }else{
            $participantInfo['bank_order'] = 0;
        }
        
        $participantRelatives = $participant->customerRelatives()->get();

        $weddingInfo = $participant->wedding()
            ->select(
                'id', 'thank_you_message', 'greeting_message', 'date', 'ceremony_reception_time',
                'ceremony_time', 'party_reception_time', 'party_time', 'place_id'
            )
            ->first();

        $place = $weddingInfo->place()
            ->select('id', 'name', 'restaurant_id')
            ->first();

        $restaurant = $place->restaurant()
            ->select('id', 'address_1', 'address_2' , 'phone')
            ->first();

        $weddingCard = $this->weddingCardRepo->model
            ->where('wedding_id', $weddingId)
            ->select('wedding_price', 'couple_photo', 'content', 'template_card_id')
            ->with(['templateCard' => function($q){
                $q->select('id', 'card_path');
            }])
            ->first();
        
        return [
            'participant' => $participant,
            'participant_info' => $participantInfo,
            'participant_relatives' => $participantRelatives,
            'wedding_info' => $weddingInfo,
            'place_name' => $place->name,
            'contact' => $restaurant,
            'wedding_card' => $weddingCard
        ];
    }

    /**
     * UI STAFF - [AS170]
     * @param $id of guest participant id
     * **/
    public function staffGetParticipant($id)
    {
        $participant = $this->customerRepo
            ->model
            ->where('id', $id)
            ->where('role', Role::GUEST)
            ->select('id', 'email', 'join_status')
            ->first();

        $participantInfo = $participant->customerInfo()
            ->select(
                'id', 'is_only_party', 'first_name', 'last_name', 'relationship_couple',
                'phone', 'post_code', 'address'
            )
            ->first();
        
        return [
            'participant' => $participant,
            'participant_info' => $participantInfo
        ];
    }

    /**
     * UI COUPLE - [U064]
     * @param $requestData as $data
     * **/
    public function coupleUpdateGuestInfo($data)
    {
        $guest = $this->customerRepo->model->find($data['id']);
        $guest->update([
                'email' => Str::lower($data['email']),
                'full_name' => $data['first_name'] . " " . $data['last_name'],
                'join_status' => $data['join_status'],
            ]);
        $guest->customerInfo()
            ->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'relationship_couple' => $data['relationship_couple'],
                'post_code' => $data['post_code'],
                'phone' => $data['phone'],
                'address' => $data['address']
            ]);
        
        return true;
    }

    /**
     * UI COUPLE - [AS170] Staff Edit Guest Info
     * @param $requestData as $data
     * **/
    public function staffUpdateGuestInfo($data)
    {
        $guest = $this->customerRepo->model->find($data['id']);

        $guest->update([
            'email' => Str::lower($data['email']),
            'full_name' => $data['first_name'] . " " . $data['last_name'],
            'join_status' => $data['join_status'],
        ]);

        $guest->customerInfo()
            ->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'relationship_couple' => $data['relationship_couple'],
                'post_code' => $data['post_code'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'is_only_party' => $data['is_only_party']
            ]);

        return true;
    }
    
}
