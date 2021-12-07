<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendMailInviteStaffJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $toEmail;
    private $content;

    public function __construct($toEmail, $content)
    {
        $this->toEmail = $toEmail;
        $this->content = $content;
    }

    public function handle()
    {
        $toEmail = $this->toEmail;
        $content = $this->content;
        
        Mail::send('mails/admin_staff_invite', $content, function($msg) use($toEmail){
            $msg->to($toEmail)->subject("Invite Registry Account Admin Staff!");
        });
    }
}
