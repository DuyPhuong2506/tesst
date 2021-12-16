<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\AgoraService;
use App\Http\Requests\StoreRtcRequest;
use App\Http\Requests\StoreRtmRequest;
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

    public function generateToken(Request $request)
    {
        try {
            $roleHost = \App\Libs\Agora\RtcTokenBuilder::RolePublisher;
            $roleMember = \App\Libs\Agora\RtcTokenBuilder::RoleSubscriber;

            $users = [
                ['id' => 1, 'username' => 'pc_cam', 'role' => $roleHost],
                ['id' => 2, 'username' => 'couple', 'role' => $roleMember],
                ['id' => 3, 'username' => 'table_1', 'role' => $roleMember],
                ['id' => 4, 'username' => 'table_2', 'role' => $roleMember],
                ['id' => 5, 'username' => 'table_3', 'role' => $roleMember],
                ['id' => 6, 'username' => 'table_4', 'role' => $roleMember],
                ['id' => 7, 'username' => 'table_5', 'role' => $roleMember],
            ];
            $dataChanels = array();
            foreach ($users as $user) {
                $channelName = $user['username'];
                $uuid = $user['id'];
                // Rtc token using video call
                // $rtcToken = $this->agoraService->getRtcToken($channelName, $uuid, $user['role']);

                // // Rtm token using chat
                // $rtmToken = $this->agoraService->getRtmToken($uuid);

                // $dataChanels[] = [
                //     'app_id' => env('AGORA_APP_ID'),
                //     'uuid' => $uuid,
                //     'chanel_name' => $channelName,
                //     'rtc_token' => $rtcToken,
                //     'rtm_token' => $rtmToken,
                //     'role' => $user['role']
                // ];
            }

            

            if (empty($dataChanels)) {
                return $this->respondError(Response::HTTP_BAD_REQUEST, 'Generate token error');
            }
            

           

            return $this->respondSuccess($dataChanels);
        } catch (Exception $e) {
            return $this->respondError(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }
    }

    public function storeRtm(StoreRtmRequest $request)
    {
        try {
            $uuid = auth()->guard('customer')->user()->id;
            $rtmToken = $this->agoraService->getRtmToken($uuid);
            return $this->respondSuccess($rtmToken);
        } catch (Exception $e) {
            return $this->respondError(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }
    }

    public function storeRtc(StoreRtcRequest $request)
    {
        try {
            $uuid = auth()->guard('customer')->user()->id;
            $rtcToken = $this->agoraService->getRtcToken($request->name, $uuid, $request->role);

            return $this->respondSuccess($rtcToken);
        } catch (Exception $e) {
            return $this->respondError(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }
    }
}
