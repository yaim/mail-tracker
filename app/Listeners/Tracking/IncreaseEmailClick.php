<?php

namespace App\Listeners\Tracking;

use App\Events\Emails\EmailOpened;
use App\Services\Contracts\Tracking\TrackingStatsUpdaterInterface as Tracker;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncreaseEmailClick implements ShouldQueue
{
    protected $tracker;

    public function __construct(Tracker $tracker)
    {
        $this->tracker = $tracker;
    }

    public function handle(EmailOpened $event)
    {
        $this->tracker->increaseClick($event->email);
    }
}
