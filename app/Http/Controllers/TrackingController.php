<?php

namespace App\Http\Controllers;

use App\Events\Emails\EmailOpened;
use App\Events\Links\LinkOpened;
use App\Http\Responses\TransparentPixelResponse;
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

    public function email($id)
    {
        $email = $this->emails->findOrFail($id);
        event(new EmailOpened($email));

        return new TransparentPixelResponse();
    }

    public function link($id)
    {
        $link = $this->links->findOrFail($id);
        event(new LinkOpened($link));

        return redirect($link->address)->header('X-Robots-Tag', 'noindex');
    }
}
