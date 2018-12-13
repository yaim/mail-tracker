<?php

namespace App\Providers;

use App\Repositories\Contracts\EmailRepositoryInterface;
use App\Repositories\Contracts\LinkRepositoryInterface;
use App\Repositories\Eloquent\EmailRepository;
use App\Repositories\Eloquent\LinkRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind(EmailRepositoryInterface::class, EmailRepository::class);
        $this->app->bind(LinkRepositoryInterface::class, LinkRepository::class);
    }
}
