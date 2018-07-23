<?php

namespace App\MailTracker\Services\Email;

use App\Exceptions\EmailNotParsedException;
use App\Exceptions\EmailAlreadySentException;
use App\MailTracker\Email;
use App\MailTracker\Services\Contracts\Email\EmailSenderInterface;
use Mail;

class EmailSender implements EmailSenderInterface
{
    protected $email;

    public function process(Email $email)
    {
        $this->email = $email;

        $this->validate();
        $this->send();
    }

    protected function send()
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

    protected function validate()
    {
        $this->checkParsed();
        $this->checkNotSent();

        return true;
    }

    protected function checkParsed()
    {
        if (!isset($this->email->parsed_at)) {
            throw new EmailNotParsedException();
        }

        return true;
    }

    protected function checkNotSent()
    {
        if (isset($this->email->sent_at)) {
            throw new EmailAlreadySentException();
        }

        return true;
    }
}
