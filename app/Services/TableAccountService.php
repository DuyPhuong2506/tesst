<?php
namespace App\Services;

use App\Constants\Role;
use App\Repositories\Customer\TableAccountRepository;

class TableAccountService
{
    protected $tableAccountRepository;

    public function __construct(TableAccountRepository $tableAccountRepository)
    {
        $this->tableAccountRepository = $tableAccountRepository;
    }

    /**
     * Get list table account of Restaurant
     */
    public function getListOfWedding(string $restaurantId)
    {
        try {
            return $this->tableAccountRepository->getListOfWedding($restaurantId);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}