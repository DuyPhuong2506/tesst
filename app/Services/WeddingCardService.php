<?php
namespace App\Services;

use App\Repositories\WeddingCardRepository;
use App\Repositories\BankAccountRepository;

class WeddingCardService
{
    protected $weddingCardRepo;
    protected $bankAccountRepo;

    public function __construct(
        WeddingCardRepository $weddingCardRepo,
        BankAccountRepository $bankAccountRepo
    ){
        $this->weddingCardRepo = $weddingCardRepo;
        $this->bankAccountRepo = $bankAccountRepo;
    }

    public function createWeddingCard($requestData)
    {
        return $requestData;
    }

}