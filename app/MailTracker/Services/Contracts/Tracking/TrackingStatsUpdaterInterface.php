<?php

namespace App\MailTracker\Services\Contracts\Tracking;

use App\MailTracker\Database\Contracts\Clickable;

interface TrackingStatsUpdaterInterface
{

    public function increaseClick(Clickable $clickable);

}
