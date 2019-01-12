<?php

namespace App\Services\Contracts\Tracking;

use App\Database\Contracts\Clickable;

interface TrackingStatsReporterInterface
{
    public function countClicks(Clickable $clickable);
}
