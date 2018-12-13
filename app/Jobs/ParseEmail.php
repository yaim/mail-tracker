<?php

namespace App\Jobs;

use App\Email;
use App\Services\Contracts\Email\EmailParserInterface as EmailParser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ParseEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function handle(EmailParser $emailParser)
    {
        $emailParser->parse($this->email);
    }
}
