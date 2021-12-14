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
        $weddingCard['wedding_id'] = $weddingId;
        $weddingCard = $this->weddingCardRepo
                            ->model
                            ->create($weddingCard);
        $weddingCard->bankAccounts()->createMany($bankAccount);

        return $this->detailWeddingCard($weddingCard->id);
    }

    public function getPreSigned($request)
    {
        $file_paths = null;
        $pre_signed = null;

        $file_info = $request->file_couple;
        $file = explode('.', $file_info);
        $extensionFile = $file[1];
        $nameFile = $file[0];
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $fileName = \Str::random(10) . '_' . $file_info;
        $filePath = 'couple/' . $fileName;
        
        $command = $client->getCommand('PutObject', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key' => $filePath,
        ]);

        $request = $client->createPresignedRequest($command, '+20 minutes');

        $filePathArray = [
            'image_path' => $filePath,
            'full_link_image' => Storage::disk('s3')->url($filePath),
            'extension' =>  $extensionFile,
        ];

        $preSignedArray = [
            'pre_signed' => (string) $request->getUri()
        ];

        $file_paths = $filePathArray;
        $pre_signed = $preSignedArray;
           
        return  [
            'couple_file_paths' => $file_paths,
            'couple_pre_signeds' => $pre_signed,
        ];
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