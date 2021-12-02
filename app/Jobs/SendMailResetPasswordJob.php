<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendMailResetPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $toEmail;
    private $emailInfo;
    
    public function __construct($toEmail, $emailInfo)
    {
        $this->toEmail = $toEmail;
        $this->emailInfo = $emailInfo;
    }

    public function handle()
    {
        $toEmail = $this->toEmail;
        $emailInfo = $this->emailInfo;

        Mail::send('mails/change_password', $emailInfo, function($msg) use($toEmail){
            $msg->to($toEmail)->subject("Change Password !");
        });
    }
}
