<?php

namespace App\Console\Commands;

use App\MailTracker\Repositories\Contracts\EmailRepositoryInterface as EmailRepository;
use App\Jobs\ParseEmail;
use Illuminate\Console\Command;

class ParseEmails extends Command
{
    protected $signature = 'emails:parse';
    protected $description = 'Places tarackable pixel and links into email';
    protected $emails;

    public function __construct(EmailRepository $emails)
    {
        $this->emails = $emails;

        parent::__construct();
    }

    public function handle()
    {
        $this->emails->getRawEmails()->each(function ($email) {
            ParseEmail::dispatch($email);
        });
    }
}
