<?php

namespace App\MailTracker\Repositories\Eloquent;

use App\MailTracker\Repositories\Contracts\EmailRepositoryInterface;
use App\MailTracker\Email;
use App\MailTracker\User;
use Illuminate\Database\Eloquent\Collection;

class EmailRepository extends AbstractRepository implements EmailRepositoryInterface {

    public function getModel()
    {
        return new Email;
    }

    public function createForUser(User $user, array $data) : Email
    {
        $this->model->fill($data);
        $user->emails()->save($this->model);

        return $this->model;
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
        return $user->emails;
    }

    public function getRawEmails(int $limit = -1) : Collection
    {
        return $this->model
                    ->whereNull('parsed_at')
                    ->limit($limit)
                    ->get();
    }

    public function getSendReadyEmails(int $limit = -1) : Collection
    {
        return $this->model
                    ->whereNotNull('parsed_at')
                    ->whereNull('sent_at')
                    ->limit($limit)
                    ->get();
    }
}
