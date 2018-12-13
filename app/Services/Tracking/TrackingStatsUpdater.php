<?php

namespace App\Services\Tracking;

use App\Services\Contracts\Tracking\TrackingStatsUpdaterInterface;
use App\Database\Contracts\Clickable;

class TrackingStatsUpdater implements TrackingStatsUpdaterInterface
{

    public function increaseClick(Clickable $clickable)
    {
        return $clickable->clicks()->create();
    }

}
