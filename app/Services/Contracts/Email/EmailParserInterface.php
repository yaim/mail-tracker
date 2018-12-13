<?php

namespace App\Services\Contracts\Email;

use App\Email;

interface EmailParserInterface
{

    public function parse(Email $email);

}
