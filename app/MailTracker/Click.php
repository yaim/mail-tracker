<?php

namespace App\MailTracker;

use App\MailTracker\Database\Eloquent\Model as Model;

class Click extends Model
{
    public function clickable()
    {
        return $this->morphTo();
    }
}
