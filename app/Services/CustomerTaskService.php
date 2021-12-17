<?php
namespace App\Services;

use App\Repositories\CustomerTaskRepository;

class CustomerTaskService
{
    protected $customerTaskRepo;

    public function __construct(CustomerTaskRepository $customerTaskRepo)
    {
        $this->customerTaskRepo = $customerTaskRepo;
    }

    public function getListCustomerTask()
    {
        return $this->customerTaskRepo->model->all();
    }
}