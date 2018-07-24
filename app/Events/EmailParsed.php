<?php

namespace App\Events;

use App\MailTracker\Email;
use App\Jobs\SendEmail;

class EmailParsed
{
    public function __construct(Email $email)
    {
        SendEmail::dispatch($email);
    }

}
