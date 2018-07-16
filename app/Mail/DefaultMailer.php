<?php

namespace App\Mail;

use App\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DefaultMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The email instance.
     *
     * @var Email
     */
    protected $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->email->from_email_address)
                    ->subject($this->email->subject)
                    ->view('emails.simple')
                    ->with([
                        'content' => $this->email->parsed_content
                    ]);
    }
}
