<?php

namespace App\Repositories\Contracts;

use App\Email;
use App\User;
use Illuminate\Pagination\AbstractPaginator;

interface EmailRepositoryInterface extends AbstractRepositoryInterface
{
    public function createForUser(User $user, array $data) : Email;

    public function findParsedOrFail(string $id) : Email;

    public function findOrFailForUser(string $id, User $user) : Email;

    public function paginateForUser(User $user) : AbstractPaginator;
}
