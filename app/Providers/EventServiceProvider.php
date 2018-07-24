<?php

namespace App\Providers;

use App\Events\EmailCreated;
use App\Events\EmailParsed;
use App\Listeners\ParseCreatedEmail;
use App\Listeners\SendParsedEmail;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        EmailCreated::class => [
            ParseCreatedEmail::class,
        ],
        EmailParsed::class => [
            SendParsedEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
