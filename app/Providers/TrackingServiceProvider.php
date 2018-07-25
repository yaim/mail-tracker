<?php

namespace App\Providers;

use App\MailTracker\Services\Contracts\Tracking\TrackingStatsUpdaterInterface;
use App\MailTracker\Services\Tracking\TrackingStatsUpdater;
use Illuminate\Support\ServiceProvider;

class TrackingServiceProvider extends ServiceProvider
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
        $this->app->bind(TrackingStatsUpdaterInterface::class, TrackingStatsUpdater::class);
    }
}
