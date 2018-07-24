<?php

namespace App\Console\Commands;

use App\MailTracker\Email;
use App\Jobs\SendEmail;
use Illuminate\Console\Command;

class SendEmails extends Command
{
    protected $signature = 'emails:send {emailID?}';

    protected $description = 'Queue parsed emails to send';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if ($emailID = $this->argument('emailID')) {
            $emails = Email::whereId($emailID)->get();
        } else {
            $emails = Email::whereNotNull('parsed_at')
                           ->whereNull('sent_at')
                           ->get();
        }

        $emails->each(function ($email) {
            SendEmail::dispatch($email);
        });
    }
}
