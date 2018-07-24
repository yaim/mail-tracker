<?php

namespace App\MailTracker\Services\Contracts\Email;

use App\MailTracker\Email;

interface EmailSenderInterface
{

    public function send(Email $email);

}
