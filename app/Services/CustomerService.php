<?php
namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Repositories\WeddingCardRepository;
use App\Repositories\EventRepository;
use Illuminate\Support\Facades\Auth;
use App\Constants\Common;
use App\Constants\Role;
use Str;

class CustomerService
{
    protected $customerRepo;
    protected $weddingCardRepo;
    protected $weddingRepo;

    public function __construct(
        CustomerRepository $customerRepo,
        WeddingCardRepository $weddingCardRepo,
        EventRepository $weddingRepo
    ){
        $this->customerRepo = $customerRepo;
        $this->weddingCardRepo = $weddingCardRepo;
        $this->weddingRepo = $weddingRepo;
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
            ->when(isset($auth->role) && in_array($auth->role, $roleWeddingIds), function($q) use ($auth) {
                $q->whereHas('wedding', function($q) use ($auth){
                    $q->whereId($auth->wedding_id)
                        ->where('is_close', Common::STATUS_FALSE);
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

    public function createParticipant($requestData, $weddingId)
    {
        $customerRelative = null;
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
                    'id', 'first_name', 'last_name', 
                    'relationship_couple', 'is_send_wedding_card',
                    'is_only_party', 'customer_id'
                );
            }])
            ->select('id', 'full_name', 'email')
            ->paginate($paginate);

        
        $dateInfo = $this->weddingRepo
            ->model
            ->where('id', $weddingId)
            ->select('guest_invitation_response_date', 'couple_edit_date')
            ->first();

        return [
            'participants' => $participants,
            'date_info' => $dateInfo
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
        $participantInfo = $this->customerRepo
            ->model
            ->where('id', $id)
            ->where('wedding_id', $weddingId)
            ->where('role', Role::GUEST)
            ->select('id', 'email')
            ->with(['customerInfo' => function($q){
                $q->select(
                    'id', 'customer_id', 'first_name', 'last_name',
                    'relationship_couple', 'post_code', 'address',
                    'phone', 'customer_type', 'task_content', 'free_word',
                    'is_send_wedding_card'
                );
            }])
            ->with(['customerRelatives' => function($q){
                $q->select(
                    'id', 'customer_id', 'first_name',
                    'last_name', 'relationship'
                );
            }])
            ->first();

        return $participantInfo;
    }
    
}
