<?php

namespace App\MailTracker\Repositories\Contracts;

use App\MailTracker\Email;

interface LinkRepositoryInterface extends AbstractRepositoryInterface {

    public function createManyForEmail(Email $email, array $data);

}
