<?php

namespace App\MailTracker\Services\Contracts\Email;

use App\MailTracker\Email;

interface EmailValidatorInterface
{
	public function setModel(Email $email);

	public function checkNotParsed();

	public function checkParsed();

	public function checkNotSent();

}
