<?php

namespace App\Listeners\Emails;

use App\Events\Emails\EmailCreated;
use App\Jobs\ParseEmail;

class ParseCreatedEmail
{
    public function handle(EmailCreated $event)
    {
        ParseEmail::dispatch($event->email);
    }
}
