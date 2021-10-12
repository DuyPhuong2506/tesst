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
                $rtcToken = $this->agoraService->getRtcToken($channelName, $uuid, $user['role']);
                $dataChanels[] = [
                    'app_id' => env('AGORA_APP_ID'),
                    'uuid' => $uuid,
                    'chanel_name' => $channelName,
                    'rtc_token' => $rtcToken,
                    'role' => $user['role']
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
