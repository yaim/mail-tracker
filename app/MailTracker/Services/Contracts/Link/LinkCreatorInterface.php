<?php

namespace App\MailTracker\Services\Contracts\Link;

use App\MailTracker\Email;

interface LinkCreatorInterface
{

    public function createMany(array $data, Email $email);

}
