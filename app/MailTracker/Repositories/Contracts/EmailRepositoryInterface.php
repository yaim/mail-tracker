<?php

namespace App\MailTracker\Repositories\Contracts;

use App\MailTracker\User;

interface EmailRepositoryInterface extends AbstractRepositoryInterface {

    public function findParsedOrFail(string $id);

    public function forUser(User $user);

    public function createForUser(User $user, array $data);
}
