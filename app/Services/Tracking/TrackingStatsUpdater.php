<?php

namespace App\Services\Tracking;

use App\Database\Contracts\Clickable;
use App\Services\Contracts\Tracking\TrackingStatsUpdaterInterface;

class TrackingStatsUpdater implements TrackingStatsUpdaterInterface
{
    public function increaseClick(Clickable $clickable)
    {
        return $clickable->clicks()->create();
    }
}
