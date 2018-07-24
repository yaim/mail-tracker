<?php

namespace App\Providers;

use App\MailTracker\Repositories\Contracts\EmailRepositoryInterface;
use App\MailTracker\Repositories\Contracts\LinkRepositoryInterface;
use App\MailTracker\Repositories\Eloquent\EmailRepository;
use App\MailTracker\Repositories\Eloquent\LinkRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EmailRepositoryInterface::class, EmailRepository::class);
        $this->app->bind(LinkRepositoryInterface::class, LinkRepository::class);
    }
}
