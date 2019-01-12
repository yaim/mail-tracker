<?php

namespace App\Services\Contracts\Link;

use App\Email;

interface LinkCreatorInterface
{
    public function createMany(array $data, Email $email);
}
