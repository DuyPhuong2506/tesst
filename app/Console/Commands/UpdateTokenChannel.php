<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AgoraService;

class UpdateTokenChannel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateTokenChannel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command create token live stream';

    /**
     * @var AgoraService
     */
    protected $agoraService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->agoraService = app(AgoraService::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = \Carbon\Carbon::now()->startOfDay();
        $channels = \DB::table('channels')
            ->where('status', STATUS_FALSE)
            ->get();
        
        foreach($channels as $channel) {
            // Rtc token using video call
            $rtcToken = $this->agoraService->getRtcToken($channel->name, $channel->id, $channel->role);

            // Rtm token using chat
            $rtmToken = $this->agoraService->getRtmToken($channel->id);

            \DB::table('channels')->where('id', $channel->id)->update([
                'rtc_token' =>  $rtcToken,
                'rtm_token' =>  $rtmToken,
                'status'    =>  STATUS_TRUE
            ]);
        }
    }
}
