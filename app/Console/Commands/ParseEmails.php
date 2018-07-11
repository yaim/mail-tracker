<?php

namespace App\Console\Commands;

use App\Email;
use Illuminate\Console\Command;

class ParseEmails extends Command
{
    protected $signature = 'emails:parse {emailID?}';

    protected $description = 'Places tarackable pixel and links into email';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if ($emailID = $this->argument('emailID')) {
            $emails = Email::whereId($emailID)->get();
        } else {
            $emails = Email::whereNull('parsed_at')->get();
        }

        $emails->parse();
    }
}
