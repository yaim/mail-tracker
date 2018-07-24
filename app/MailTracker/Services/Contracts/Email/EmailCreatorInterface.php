<?php

namespace App\MailTracker\Services\Contracts\Email;

use App\MailTracker\Email;
use App\MailTracker\User;

interface EmailCreatorInterface
{
    public function __construct(Email $email);

    public function create(array $data, User $user);

}
