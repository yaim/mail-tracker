<?php

namespace App\Events\Links;

use App\MailTracker\Link;

class LinkOpened
{
    public $link;

    public function __construct(Link $link)
    {
        $this->link = $link;
    }
}
