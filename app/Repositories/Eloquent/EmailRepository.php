<?php

namespace App\Repositories\Eloquent;

use App\Email;
use App\Repositories\Contracts\EmailRepositoryInterface;
use App\Services\Contracts\Email\EmailCreatorInterface as EmailCreator;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\AbstractPaginator;

class EmailRepository extends AbstractRepository implements EmailRepositoryInterface
{
    protected $creator;

    public function __construct(EmailCreator $creator)
    {
        $this->creator = $creator;

        parent::__construct();
    }

    public function getModel()
    {
        return new Email();
    }

    public function createForUser(User $user, array $data) : Email
    {
        return $this->creator->create($data, $user);
    }

    public function findParsedOrFail(string $id) : Email
    {
        return $this->model
                    ->where($this->model->getKeyName(), $id)
                    ->whereNotNull('parsed_at')
                    ->firstOrFail();
    }

    public function forUser(User $user) : Collection
    {
        return $this->filterUserEmails($user)
                    ->get();
    }

    public function findOrFailForUser(string $id, User $user) : Email
    {
        return $this->model
                    ->where($this->model->getKeyName(), $id)
                    ->where('user_id', $user->id)
                    ->firstOrFail();
    }

    public function paginateForUser(User $user) : AbstractPaginator
    {
        return $this->filterUserEmails($user)
                    ->paginate();
    }

    private function filterUserEmails(User $user) : Relation
    {
        return $user->emails()
                    ->orderBy('created_at', 'desc');
    }
}
