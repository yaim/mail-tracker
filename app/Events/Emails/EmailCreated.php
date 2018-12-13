<?php

namespace App\Events\Emails;

use App\Email;

class EmailCreated
{
    public $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

}
