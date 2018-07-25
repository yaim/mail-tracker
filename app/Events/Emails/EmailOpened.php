<?php

namespace App\Events\Emails;

use App\MailTracker\Email;

class EmailOpened
{
    public $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }
}
