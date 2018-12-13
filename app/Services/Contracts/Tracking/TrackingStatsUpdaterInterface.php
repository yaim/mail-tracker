<?php

namespace App\Services\Contracts\Tracking;

use App\Database\Contracts\Clickable;

interface TrackingStatsUpdaterInterface
{

    public function increaseClick(Clickable $clickable);

}
