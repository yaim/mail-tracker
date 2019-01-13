<?php

namespace App\Services\Email;

use App\Email;
use App\Mail\RawMailable;
use App\Services\Contracts\Email\EmailSenderInterface;
use App\Services\Contracts\Email\EmailValidatorInterface as EmailValidator;
use Mail;

class EmailSender implements EmailSenderInterface
{
    protected $email;
    protected $validator;

    public function __construct(EmailValidator $validator)
    {
        $this->validator = $validator;
    }

    public function send(Email $email)
    {
        $this->email = $email;

        $this->validate();
        $this->process();
    }

    protected function validate()
    {
        $this->validator
             ->setModel($this->email)
             ->checkParsed()
             ->checkNotSent();
    }

    protected function process()
    {
        Mail::send(new RawMailable($this->email));

        $this->email->sent_at = now();
        $this->email->save();
    }
}
