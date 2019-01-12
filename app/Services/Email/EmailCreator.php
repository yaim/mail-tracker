<?php

namespace App\Services\Email;

use App\Email;
use App\Services\Contracts\Email\EmailCreatorInterface;
use App\User;

class EmailCreator implements EmailCreatorInterface
{
    protected $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function create(array $data, User $user = null)
    {
        if ($user) {
            $this->email->user_id = $user->id;
        }

        $this->email->fill($data);
        $this->email->save();

        return $this->email;
    }
}
