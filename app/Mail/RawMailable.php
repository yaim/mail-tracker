<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Container\Container;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RawMailable extends Mailable
{
    use Queueable, SerializesModels;

    protected $parsedEmail;

    public function __construct($parsedEmail)
    {
        $this->parsedEmail = $parsedEmail;
    }

    public function build()
    {
        $this->from($this->parsedEmail->from_email_address)
                ->to($this->parsedEmail->to_email_address)
                ->subject($this->parsedEmail->subject);
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
        Container::getInstance()->call([ $this, 'build' ]);

        $content = $this->parsedEmail->parsed_content;

        $mailer->send([ ], [ ], function($message) use ($content) {
            $message->setBody($content, 'text/html');

            $this->buildFrom($message)
                    ->buildRecipients($message)
                    ->buildSubject($message)
                    ->buildAttachments($message)
                    ->runCallbacks($message);
        });
    }

    public function getParsedEmail()
    {
        return $this->parsedEmail;
    }
}
