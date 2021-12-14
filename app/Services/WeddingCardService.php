<?php
namespace App\Services;

use App\Repositories\WeddingCardRepository;
use App\Repositories\BankAccountRepository;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

    public function createWeddingCard($weddingCard, $bankAccount, $weddingId)
    {
        $couplePhoto = $weddingCard['couple_photo'];
        $weddingCard['couple_photo'] = $this->storeCouplePhoto($couplePhoto);
        $weddingCard['wedding_id'] = $weddingId;
        $weddingCard = $this->weddingCardRepo
                            ->model
                            ->create($weddingCard);
        $weddingCard->bankAccounts()->createMany($bankAccount);

        return $this->detailWeddingCard($weddingCard->id);
    }

    public function storeCouplePhoto($file)
    {
        $linkS3 = null;
        if ($file){
            $nameDirectory = 'couple/';
            $fullName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $nameFile = \Str::random(10) . '_' . $fullName;

            $img = Image::make($file)->fit(400)->stream();
            $linkS3 = Storage::disk('s3')->put(
                $nameDirectory . $nameFile,
                $img->__toString()
            );
            $linkS3 = $nameDirectory . $nameFile;
        }

        return $linkS3;
    }

    public function detailWeddingCard($id)
    {
        $data = $this->weddingCardRepo
                     ->model
                     ->where('id', $id)
                     ->with(['bankAccounts'])
                     ->first();

        $disk = Storage::disk('s3');
        $couplePhoto = $disk->url($data['couple_photo']);
        $data['couple_photo'] = $couplePhoto;
        
        return $data;
    }

}