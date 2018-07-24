<?php

namespace App\MailTracker\Repositories\Eloquent;

use App\MailTracker\Repositories\Contracts\EmailRepositoryInterface;
use App\MailTracker\Email;
use App\MailTracker\User;

class EmailRepository extends AbstractRepository implements EmailRepositoryInterface {
    public function getModel()
    {
        return new Email;
    }

    public function forUser(User $user)
    {
        return $user->emails;
    }

    public function createForUser(User $user, array $data)
    {
        $this->model->fill($data);
        $user->emails()->save($this->model);

        return $this->model;
    }

    public function findParsedOrFail(string $id)
    {
        return $this->model
                    ->where($this->model->getKeyName(), $id)
                    ->whereNotNull('parsed_at')
                    ->firstOrFail();
    }
}
