<?php

namespace App\Providers;

use App\Services\Contracts\Tracking\TrackingStatsReporterInterface;
use App\Services\Contracts\Tracking\TrackingStatsUpdaterInterface;
use App\Services\Tracking\TrackingStatsReporter;
use App\Services\Tracking\TrackingStatsUpdater;
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
        $this->app->bind(TrackingStatsReporterInterface::class, TrackingStatsReporter::class);
        $this->app->bind(TrackingStatsUpdaterInterface::class, TrackingStatsUpdater::class);
    }
}
