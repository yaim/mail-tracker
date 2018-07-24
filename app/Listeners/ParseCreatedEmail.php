<?php

namespace App\Listeners;

use App\Events\EmailCreated;
use App\Jobs\ParseEmail;

class ParseCreatedEmail
{
    public function handle(EmailCreated $event)
    {
        ParseEmail::dispatch($event->email);
    }
}
