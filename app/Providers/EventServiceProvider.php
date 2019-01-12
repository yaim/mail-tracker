<?php

namespace App\Providers;

use App\Events\Emails\EmailCreated;
use App\Events\Emails\EmailOpened;
use App\Events\Emails\EmailParsed;
use App\Events\Links\LinkOpened;
use App\Listeners\Emails\ParseCreatedEmail;
use App\Listeners\Emails\SendParsedEmail;
use App\Listeners\Tracking\IncreaseEmailClick;
use App\Listeners\Tracking\IncreaseLinkClick;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        EmailOpened::class => [
            IncreaseEmailClick::class,
        ],
        LinkOpened::class => [
            IncreaseLinkClick::class,
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
