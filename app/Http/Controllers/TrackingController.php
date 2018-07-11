<?php

namespace App\Http\Controllers;

use App\Email;
use App\Link;

class TrackingController extends Controller
{
    private function pixelResponse()
    {
        $pixel = base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');

        return response($pixel, 200)->header('Content-Type', 'image/gif');
    }

    public function email($uuid)
    {
        $email = Email::whereUuid($uuid)->firstOrFail();

        return $this->pixelResponse();
    }

    public function link($uuid)
    {
        $link = Link::whereUuid($uuid)->firstOrFail();

        return redirect($link->address);
    }
}
