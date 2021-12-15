<?php
namespace App\Services;

use App\Repositories\BankAccountRepository;
use App\Repositories\WeddingCardRepository;

class BankAccountService
{
    protected $bankAccountRepo;
    protected $weddingCardRepo;

    public function __construct(
        BankAccountRepository $bankAccountRepo,
        WeddingCardRepository $weddingCardRepo
    ){
        $this->bankAccountRepo = $bankAccountRepo;
        $this->weddingCardRepo = $weddingCardRepo;
    }

    public function updateOrCreateBankAccount($bankAccounts, $weddingPrice, $weddingId)
    {
        $weddingCard = $this->weddingCardRepo->model->where('wedding_id', $weddingId)
                                                    ->firstOrFail();

        $weddingCard->bankAccounts()->delete();
        $updatedWeddingCard = $weddingCard->update($weddingPrice);
        $updatedbankAccount = $weddingCard->bankAccounts()->createMany($bankAccounts); 
        
        return [
            'wedding_card' => $weddingCard,
            'bank_accounts' => $updatedbankAccount
        ];
    }
}