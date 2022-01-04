<?php
namespace App\Services;

use App\Repositories\WeddingCardRepository;
use App\Repositories\BankAccountRepository;
use App\Repositories\EventRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\CustomerInfoRepository;
use App\Jobs\SendDoneCardToStaffJob;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Constants\Role;
use App\Constants\InviteSend;
use App\Constants\NotifyPlannerConstant;

class WeddingCardService
{
    protected $weddingCardRepo;
    protected $bankAccountRepo;
    protected $weddingRepo;
    protected $customerRepo;
    protected $customerInfoRepo;

    public function __construct(
        WeddingCardRepository $weddingCardRepo,
        BankAccountRepository $bankAccountRepo,
        EventRepository $weddingRepo,
        CustomerRepository $customerRepo,
        CustomerInfoRepository $customerInfoRepo
    ){
        $this->weddingCardRepo = $weddingCardRepo;
        $this->bankAccountRepo = $bankAccountRepo;
        $this->weddingRepo = $weddingRepo;
        $this->customerRepo = $customerRepo;
        $this->customerInfoRepo = $customerInfoRepo;
    }

    public function createWeddingCard($cardData, $weddingId)
    {
        $wedding = $this->weddingRepo->model->find($weddingId);
        $weddingCard = $wedding->weddingCard();
        
        $weddingCard = $weddingCard->updateOrCreate(
            ['wedding_id' => $weddingId],
            $cardData
        );

        return true;
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
            'image_name' => $fileName,
            'full_link_image' => Storage::disk('s3')->url($filePath),
            'extension' =>  $extensionFile,
        ];

        $preSignedArray = [
            'pre_signed' => (string) $request->getUri()
        ];

        $file_paths = $filePathArray;
        $pre_signed = $preSignedArray;
           
        return  [
            'couple_pre_signeds' => $pre_signed,
            'file_path' => $file_paths
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
            ->with(['bankAccounts', 'templateCard'])
            ->with(['wedding' => function($q){
                $q->select('id', 'guest_invitation_response_date', 'couple_edit_date');
            }])
            ->first();
        
        return $data;
    }

    public function notifyToStaff($weddingID)
    {
        $exits = $this->weddingCardRepo->model
            ->where('wedding_id', $weddingID)
            ->exists();

        if($exits){
            $wedding = $this->weddingRepo->model->find($weddingID);
            $place = $wedding->place()->first();
            $restaurant = $place->restaurant()->first();
            $staff = $restaurant->user()->first();
            $customers = $wedding->customers()
                ->where('role', Role::GROOM)
                ->select('full_name')
                ->first();

            $wedding->update([
                'is_notify_planner' => NotifyPlannerConstant::SENT
            ]);

            $staffEmail = $staff->email;
            $contentEmail = [
                'contactName' => $restaurant->contact_name,
                'customerName' => $customers->full_name,
                'appURL' => env('APP_URL'),
            ];

            $sendEmailJob = new SendDoneCardToStaffJob($staffEmail, $contentEmail);
            dispatch($sendEmailJob);

            return true;
        }

        return false;
    }

}