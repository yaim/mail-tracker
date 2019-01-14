<?php

namespace App\Repositories\Eloquent;

use App\Email;
use App\Repositories\Contracts\EmailRepositoryInterface;
use App\Services\Contracts\Email\EmailCreatorInterface as EmailCreator;
use App\User;
use Illuminate\Database\Eloquent\Collection;

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
        return $user->emails()
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    public function findOrFailForUser(string $id, User $user) : Email
    {
        return $this->model
                    ->where($this->model->getKeyName(), $id)
                    ->where('user_id', $user->id)
                    ->firstOrFail();
    }
}
