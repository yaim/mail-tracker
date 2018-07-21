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

    public function email($id)
    {
        $email = Email::findOrFail($id);
        $email->clicks()->create();

        return $this->pixelResponse();
    }

    public function link($id)
    {
        $link = Link::findOrFail($id);
        $link->clicks()->create();

        return redirect($link->address);
    }
}
