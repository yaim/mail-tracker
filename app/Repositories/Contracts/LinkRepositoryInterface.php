<?php

namespace App\Repositories\Contracts;

use App\Email;

interface LinkRepositoryInterface extends AbstractRepositoryInterface
{
    public function createManyForEmail(Email $email, array $data);
}
