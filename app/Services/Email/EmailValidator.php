<?php

namespace App\Services\Email;

use App\Exceptions\EmailAlreadyParsedException;
use App\Exceptions\EmailAlreadySentException;
use App\Exceptions\EmailNotParsedException;
use App\Email;
use App\Services\Contracts\Email\EmailValidatorInterface;

class EmailValidator implements EmailValidatorInterface
{
    protected $email;

    public function setModel(Email $email)
    {
        $this->email = $email;

        return $this;
    }

    public function checkNotParsed()
    {
        if (isset($this->email->parsed_at)) {
            throw new EmailAlreadyParsedException();
        }

        return $this;
    }

    public function checkParsed()
    {
        if (!isset($this->email->parsed_at)) {
            throw new EmailNotParsedException();
        }

        return $this;
    }

    public function checkNotSent()
    {
        if (isset($this->email->sent_at)) {
            throw new EmailAlreadySentException();
        }

        return $this;
    }

}
