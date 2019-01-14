<?php

namespace App\Providers;

use App\Services\Contracts\Email\EmailCreatorInterface;
use App\Services\Contracts\Email\EmailParserInterface;
use App\Services\Contracts\Email\EmailSenderInterface;
use App\Services\Email\EmailCreator;
use App\Services\Email\EmailParser;
use App\Services\Email\EmailSender;
use Illuminate\Support\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
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
        $this->app->bind(EmailCreatorInterface::class, EmailCreator::class);
        $this->app->bind(EmailParserInterface::class, EmailParser::class);
        $this->app->bind(EmailSenderInterface::class, EmailSender::class);
    }
}
