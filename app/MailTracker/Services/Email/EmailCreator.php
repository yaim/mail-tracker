<?php

namespace App\MailTracker\Services\Email;

use App\MailTracker\Email;
use App\MailTracker\User;
use App\MailTracker\Services\Contracts\Email\EmailCreatorInterface;

class EmailCreator implements EmailCreatorInterface
{
    protected $email;

    public function __construct()
    {
        $this->email = new Email;
    }

    public function create( array $data, User $user = null)
    {
        if ($user) {
            $this->email->user_id = $user->id;
        }

        $this->email->fill($data);
        $this->email->save();

        return $this->email;
    }

}
