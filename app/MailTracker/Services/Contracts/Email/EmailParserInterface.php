<?php

namespace App\MailTracker\Services\Contracts\Email;

use App\MailTracker\Email;

interface EmailParserInterface {

    public function process(Email $email);

}
