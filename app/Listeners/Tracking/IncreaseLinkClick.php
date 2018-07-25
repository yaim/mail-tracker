<?php

namespace App\Listeners\Tracking;

use App\Events\Links\LinkOpened;
use App\MailTracker\Services\Contracts\Tracking\TrackingStatsUpdaterInterface as Tracker;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncreaseLinkClick implements ShouldQueue
{
    protected $tracker;

    public function __construct(Tracker $tracker)
    {
        $this->tracker = $tracker;
    }

    public function handle(LinkOpened $event)
    {
        $this->tracker->increaseClick($event->link);
    }
}
