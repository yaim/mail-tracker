<?php

namespace App;

use App\Database\Eloquent\Model as Model;

class Click extends Model
{
    public function clickable()
    {
        return $this->morphTo();
    }
}
