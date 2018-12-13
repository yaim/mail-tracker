<?php

namespace App\Services\Link;

use App\Email;
use App\Services\Contracts\Link\LinkCreatorInterface;

class LinkCreator implements LinkCreatorInterface
{

    public function createMany( array $data, Email $email)
    {
        return $email->links()->createMany($data);
    }

}
