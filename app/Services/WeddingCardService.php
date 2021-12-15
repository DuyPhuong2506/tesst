<?php
namespace App\Services;

use App\Repositories\WeddingCardRepository;
use App\Repositories\BankAccountRepository;
use App\Repositories\EventRepository;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class WeddingCardService
{
    protected $weddingCardRepo;
    protected $bankAccountRepo;
    protected $weddingRepo;

    public function __construct(
        WeddingCardRepository $weddingCardRepo,
        BankAccountRepository $bankAccountRepo,
        EventRepository $weddingRepo
    ){
        $this->weddingCardRepo = $weddingCardRepo;
        $this->bankAccountRepo = $bankAccountRepo;
        $this->weddingRepo = $weddingRepo;
    }

    public function createWeddingCard($weddingCard, $weddingId)
    {
        $wedding = $this->weddingRepo->model->find($weddingId);
        $this->removeImageCoupleS3($weddingId);
        $weddingCard = $wedding->weddingCard()->updateOrCreate(
            ['wedding_id' => $weddingId],
            $weddingCard
        );

        return $this->detailWeddingCard($weddingCard->id);
    }

    public function updateCardContent($cardContent, $weddingId)
    {
        $this->weddingCardRepo
             ->model
             ->where('wedding_id', $weddingId)
             ->update($cardContent);
        
        return $cardContent;
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

    public function showWeddingCard($weddingId)
    {
        $data = $this->weddingCardRepo
                     ->model
                     ->where('wedding_id', $weddingId)
                     ->with(['bankAccounts'])
                     ->first();

        $disk = Storage::disk('s3');
        $couplePhoto = $disk->url($data['couple_photo']);
        $data['couple_photo'] = $couplePhoto;
        
        return $data;
    }

    public function removeImageCoupleS3($weddingId)
    {   
        $couplePhoto = $this->weddingCardRepo
                            ->model
                            ->where('wedding_id', $weddingId)
                            ->first()->couple_photo;
        $path = $couplePhoto;
        Storage::disk('s3')->delete($path);
    }

}