<?php

namespace App\Services\Email;

use App\Email;
use App\Mail\RawMailable;
use App\Services\Contracts\Email\EmailSenderInterface;
use Mail;

class EmailSender implements EmailSenderInterface
{
    protected $email;

    public function send(Email $email)
    {
        $this->email = $email;

        $this->process();
    }

    protected function process()
    {
        Mail::send(new RawMailable($this->email));

        $this->email->sent_at = now();
        $this->email->save();
    }
}
