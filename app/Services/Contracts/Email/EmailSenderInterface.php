<?php

namespace App\Services\Contracts\Email;

use App\Email;

interface EmailSenderInterface
{
    public function send(Email $email);
}
