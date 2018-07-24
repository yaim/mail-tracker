<?php

namespace App\MailTracker\Services\Email;

use App\MailTracker\Email;
use App\MailTracker\Services\Contracts\Email\EmailSenderInterface;
use App\MailTracker\Services\Contracts\Email\EmailValidatorInterface as EmailValidator;
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
        $data = $this->getSendingData();

        Mail::send([], [], function($message) use ($data) {
            $message->from($data['from']);
            $message->to($data['to']);
            $message->subject($data['subject']);
            $message->setBody($data['content'], 'text/html');
        });

        $this->email->sent_at = now();
        $this->email->save();
    }

    protected function getSendingData()
    {
        return [
            'from'    => $this->email->from_email_address,
            'to'      => $this->email->to_email_address,
            'subject' => $this->email->subject,
            'content' => $this->email->parsed_content
        ];
    }

}
