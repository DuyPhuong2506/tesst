<?php
namespace App\Services;

use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Auth;
use App\Constants\Common;
use App\Constants\Role;

class CustomerService
{
    protected $customerRepo;

    public function __construct(CustomerRepository $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    public function getListCustomerInWedding(array $data)
    {
        $orderBy = isset($data['order_by']) ? explode('|', $data['order_by']) : [];
        $keyword = !empty($data['keyword']) ? escape_like($data['keyword']) : null;
        $paginate = !empty($data['paginate']) ? $data['paginate'] : Common::PAGINATE;
        $auth = Auth::guard('customer')->user();
        $roleWeddingIds = [Role::GUEST, Role::BRIDE, Role::GROOM];
        $roleTableIds = [Role::STAGE_TABLE, Role::COUPE_TABLE, Role::SPEECH_TABLE, Role::NORMAL_TABLE];
        
        $getList = $this->customerRepo->model
            ->when(isset($auth->role) && in_array($auth->role, $roleWeddingIds), function($q) use ($auth) {
                $q->whereHas('wedding', function($q) use ($auth){
                    $q->whereId($auth->wedding_id)
                        ->where('is_close', Common::STATUS_FALSE);
                });
            })
            ->when(isset($auth->role) && in_array($auth->role, $roleTableIds), function($q) use ($auth) {
                $q->whereHas('wedding', function($q) use ($auth){
                    $q->where('place_id', $auth->place_id)
                        ->where('is_close', Common::STATUS_FALSE);
                });
            });
            

        if($paginate != Common::PAGINATE_ALL){
            $getList = $getList->paginate($paginate);
        } else {
            $getList = $getList->get();
        }

        return $getList;
    }
}
