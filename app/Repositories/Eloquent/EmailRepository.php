<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\EmailRepositoryInterface;
use App\Services\Contracts\Email\EmailCreatorInterface as EmailCreator;
use App\Email;
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
        return new Email;
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

    public function getRawEmails(int $limit = -1) : Collection
    {
        return $this->model
                    ->whereNull('parsed_at')
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }

    public function getSendReadyEmails(int $limit = -1) : Collection
    {
        return $this->model
                    ->whereNotNull('parsed_at')
                    ->whereNull('sent_at')
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }
}
