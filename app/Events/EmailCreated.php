<?php

namespace App\Events;

use App\MailTracker\Email;
use App\Jobs\ParseEmail;

class EmailCreated
{
    public function __construct(Email $email)
    {
        ParseEmail::dispatch($email);
    }

}
