<?php

namespace App\Listeners\Emails;

use App\Events\Emails\EmailParsed;
use App\Jobs\SendEmail;

class SendParsedEmail
{
    public function handle(EmailParsed $event)
    {
        SendEmail::dispatch($event->email);
    }
}
