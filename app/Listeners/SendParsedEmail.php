<?php

namespace App\Listeners;

use App\Events\EmailParsed;
use App\Jobs\SendEmail;

class SendParsedEmail
{
    public function handle(EmailParsed $event)
    {
        SendEmail::dispatch($event->email);
    }
}
