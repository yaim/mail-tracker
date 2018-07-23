<?php

namespace App\Console\Commands;

use App\MailTracker\Repositories\Contracts\EmailRepositoryInterface as EmailRepository;
use App\Jobs\SendEmail;
use Illuminate\Console\Command;

class SendEmails extends Command
{
    protected $signature = 'emails:send';
    protected $description = 'Queue parsed emails to send';
    protected $emails;

    public function __construct(EmailRepository $emails)
    {
        $this->emails = $emails;

        parent::__construct();
    }

    public function handle()
    {
        $this->emails->getSendReadyEmails()->each(function ($email) {
            SendEmail::dispatch($email);
        });
    }
}
