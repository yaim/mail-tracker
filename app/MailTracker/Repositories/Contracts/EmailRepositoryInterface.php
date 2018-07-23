<?php

namespace App\MailTracker\Repositories\Contracts;

use App\MailTracker\Email;
use App\MailTracker\User;
use Illuminate\Database\Eloquent\Collection;

interface EmailRepositoryInterface extends AbstractRepositoryInterface {

    public function createForUser(User $user, array $data) : Email;

    public function findParsedOrFail(string $id) : Email;

    public function forUser(User $user) : Collection;

    public function getSendReadyEmails(int $limit) : Collection;
}
