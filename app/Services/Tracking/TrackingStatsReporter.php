<?php

namespace App\Services\Tracking;

use App\Services\Contracts\Tracking\TrackingStatsReporterInterface;
use App\Database\Contracts\Clickable;

class TrackingStatsReporter implements TrackingStatsReporterInterface
{

    public function countClicks(Clickable $clickable)
    {
        return $clickable->clicks()->count();
    }

}
