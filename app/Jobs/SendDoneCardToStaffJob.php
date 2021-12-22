<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendDoneCardToStaffJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $sendTo;
    private $content;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sendTo, $content)
    {
        $this->sendTo = $sendTo;
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sendTo = $this->sendTo;
        $content = $this->content;

        Mail::send('mails/notify_couple_done_card', $content, function($msg) use($sendTo){
            $msg->to($sendTo)->subject(__('messages.wedding_card.subject_to_staff'));
        });
    }
}
