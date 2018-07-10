<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    public function email()
    {
    	return $this->belongsTo(Email::class);
    }
}
