<?php

namespace App\Exceptions;

use Exception;

class EmailAlreadyParsedException extends Exception
{
    public function report()
    {
    }

    public function render($request)
    {
    }
}
