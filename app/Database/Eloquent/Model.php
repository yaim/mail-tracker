<?php

namespace App\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class Model extends Eloquent
{
    use SoftDeletes;

    protected $dates = [ 'deleted_at' ];
}
