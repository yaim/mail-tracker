<?php

namespace App\Http\Controllers;

use App\MailTracker\Repositories\Contracts\EmailRepositoryInterface as EmailRepository;
use App\MailTracker\Repositories\Contracts\LinkRepositoryInterface as LinkRepository;

class TrackingController extends Controller
{
    protected $emails;
    protected $links;

    public function __construct(EmailRepository $emails, LinkRepository $links)
    {
        $this->emails = $emails;
        $this->links = $links;
    }

    private function pixelResponse()
    {
        $pixel = base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');

        return response($pixel, 200)->header('Content-Type', 'image/gif');
    }

    public function email($id)
    {
        $email = $this->emails->findOrFail($id);
        $email->clicks()->create();

        return $this->pixelResponse();
    }

    public function link($id)
    {
        $link = $this->links->findOrFail($id);
        $link->clicks()->create();

        return redirect($link->address);
    }
}
