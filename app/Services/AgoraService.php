<?php

namespace App\Services;

use App\Libs\Agora\AccessToken;
use App\Libs\Agora\RtmTokenBuilder;
use App\Libs\Agora\RtcTokenBuilder;
use Carbon\Carbon;
use Log;

class AgoraService
{
    private $appID;
    private $appCertificate;

    public function __construct()
    {
        $this->appID = env('AGORA_APP_ID');
        $this->appCertificate = env('AGORA_APP_CERTIFICATE');
    }

    /*  RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);*/
    public function getRtcToken(string $channelName, int $uid = 0, $role = RtcTokenBuilder::RoleSubscriber, int $expireTimestamp = 0)
    {
        try {
            $currentTimestamp =  Carbon::now()->timestamp;
            $privilegeExpiredTs = $currentTimestamp + $expireTimestamp;
            
            $token = RtcTokenBuilder::buildTokenWithUid(
                $this->appID,
                $this->appCertificate,
                $channelName,
                $uid,
                $role,
                $privilegeExpiredTs
            );
            return $token;
        } catch (\Exception $e) {
            Log::error('[AGORA_GENERATE_RTC_TOKEN_ERROR] '. $e->getMessage());
            return false;
        }
    }

    public function getRtmToken(string $channelName, int $expireTimestamp = 0)
    {
        try {
            $token = RtmTokenBuilder::buildToken(
                $this->appID,
                $this->appCertificate,
                $channelName,
                RtmTokenBuilder::ROLE_RTM_USER,
                $expireTimestamp
            );

            return $token;
        } catch (\Exception $e) {
            Log::error('[AGORA_GENERATE_RTM_TOKEN_ERROR] '. $e->getMessage());

            return false;
        }
    }
}
