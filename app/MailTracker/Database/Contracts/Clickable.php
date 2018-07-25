<?php

namespace App\MailTracker\Database\Contracts;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

interface Clickable
{
    public function clicks();
}
