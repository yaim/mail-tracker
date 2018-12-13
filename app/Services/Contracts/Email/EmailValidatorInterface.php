<?php

namespace App\Services\Contracts\Email;

use App\Email;

interface EmailValidatorInterface
{
	public function setModel(Email $email);

	public function checkNotParsed();

	public function checkParsed();

	public function checkNotSent();

}
