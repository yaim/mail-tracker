<?php

namespace App\Mail;

use App\Database\Contracts\Mailable as MailableModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RawMailable extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;

    public function __construct(MailableModel $email)
    {
        $this->email = $email;
    }

    public function build()
    {
        $this->from($this->email->getFrom())
             ->to($this->email->getTo())
             ->subject($this->email->getSubject())
             ->view('emails.simple')
             ->with(['content' => $this->email->getContent()]);
    }
}
