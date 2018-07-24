<?php

namespace App\Providers;

use App\MailTracker\Services\Contracts\Link\LinkCreatorInterface;
use App\MailTracker\Services\Link\LinkCreator;
use Illuminate\Support\ServiceProvider;

class LinkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LinkCreatorInterface::class, LinkCreator::class);
    }
}
