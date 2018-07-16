<?php

namespace App\Exceptions;

use Exception;

class EmailAlreadySentException extends Exception
{
    public function report()
    {
    }

    public function render($request)
    {
    }
}
