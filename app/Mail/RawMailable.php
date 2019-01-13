<?php

namespace App\Mail;

use App\Database\Contracts\Mailable as MailableModel;
use Illuminate\Bus\Queueable;
use Illuminate\Container\Container;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
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
             ->subject($this->email->getSubject());
    }

    /**
     * Overwrite Mailable@send to avoid using Blade.
     *
     * @param \Illuminate\Contracts\Mail\Mailer $mailer
     *
     * @return void
     */
    public function send(MailerContract $mailer)
    {
        Container::getInstance()->call([$this, 'build']);

        $content = $this->email->getContent();

        $mailer->send([], [], function ($message) use ($content) {
            $message->setBody($content, 'text/html');

            $this->buildFrom($message)
                 ->buildRecipients($message)
                 ->buildSubject($message)
                 ->buildAttachments($message)
                 ->runCallbacks($message);
        });
    }

    public function getEmail()
    {
        return $this->email;
    }
}
