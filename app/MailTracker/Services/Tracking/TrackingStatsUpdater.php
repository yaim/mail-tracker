<?php

namespace App\MailTracker\Services\Tracking;

use App\MailTracker\Services\Contracts\Tracking\TrackingStatsUpdaterInterface;
use App\MailTracker\Database\Contracts\Clickable;

class TrackingStatsUpdater implements TrackingStatsUpdaterInterface
{

    public function increaseClick(Clickable $clickable)
    {
        return $clickable->clicks()->create();
    }

}
