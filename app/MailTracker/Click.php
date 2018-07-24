<?php

namespace App\MailTracker;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    public function clickable()
    {
        return $this->morphTo();
    }
}
