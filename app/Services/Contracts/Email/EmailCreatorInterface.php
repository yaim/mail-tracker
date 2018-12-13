<?php

namespace App\Services\Contracts\Email;

use App\Email;
use App\User;

interface EmailCreatorInterface
{
    public function __construct(Email $email);

    public function create(array $data, User $user);

}
