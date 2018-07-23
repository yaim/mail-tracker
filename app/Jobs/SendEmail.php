<?php

namespace App\Jobs;

use App\MailTracker\Email;
use App\MailTracker\Services\Contracts\Email\EmailSenderInterface as EmailSender;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function handle(EmailSender $emailSender)
    {
        $emailSender->process($this->email);
    }
}
