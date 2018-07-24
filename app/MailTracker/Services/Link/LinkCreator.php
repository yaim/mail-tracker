<?php

namespace App\MailTracker\Services\Link;

use App\MailTracker\Email;
use App\MailTracker\Services\Contracts\Link\LinkCreatorInterface;

class LinkCreator implements LinkCreatorInterface
{

    public function createMany( array $data, Email $email)
    {
        return $email->links()->createMany($data);
    }

}
