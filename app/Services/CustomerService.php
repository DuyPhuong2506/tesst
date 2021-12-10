<?php
namespace App\Services;

use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Auth;
use App\Constants\Common;

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
        
        $auth = Auth::guard('table_account')->user();
        $getList = $this->customerRepo->model->whereHas('wedding', function($q) use ($auth){
            $q->where('place_id', $auth->place_id)
                ->where('is_close', Common::STATUS_FALSE);
        });

        if($paginate != Common::PAGINATE_ALL){
            $getList = $getList->paginate($paginate);
        } else {
            $getList = $getList->get();
        }

        return $getList;
    }
}
