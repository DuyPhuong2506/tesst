<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\AgoraService;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;
use Exception;

class AgoraController extends Controller
{
     /**
     * @var AgoraService
     */
    protected $agoraService;

    public function __construct()
    {
        $this->agoraService = app(AgoraService::class);
    }

    public function generateToken()
    {
        try {
            $user = Auth::user();
            $dataChanels = array();
            for ($i = 0; $i< 5; $i++) {
                $channelName = (string) Uuid::generate(4);
                $uuid = rand(1,10000);
                // Rtc token using video call
                $rtcToken = $this->agoraService->getRtcToken($channelName, $uuid);
                $dataChanels[] = [
                    'app_id' => env('AGORA_APP_ID'),
                    'uuid' => $uuid,
                    'chanel_name' => $channelName,
                    'rtc_token' => $rtcToken
                ];
            }

            // Rtm token using chat
            //$rtmToken = $this->agoraService->getRtmToken($channelName);

            if (empty($dataChanels)) {
                return $this->respondError(Response::HTTP_BAD_REQUEST, 'Generate token error');
            }
            

           

            return $this->respondSuccess($dataChanels);
        } catch (Exception $e) {
            return $this->respondError(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }
    }
}
