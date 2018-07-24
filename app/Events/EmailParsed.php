<?php

namespace App\Events;

use App\MailTracker\Email;

class EmailParsed
{
    public $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

}
