<?php

namespace App\Services\Tracking;

use App\Database\Contracts\Clickable;
use App\Services\Contracts\Tracking\TrackingStatsReporterInterface;

class TrackingStatsReporter implements TrackingStatsReporterInterface
{
    public function countClicks(Clickable $clickable)
    {
        return $clickable->clicks()->count();
    }
}
