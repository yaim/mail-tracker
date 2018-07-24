<?php

namespace App\MailTracker\Repositories\Eloquent;

use App\MailTracker\Repositories\Contracts\LinkRepositoryInterface;
use App\MailTracker\Link;

class LinkRepository extends AbstractRepository implements LinkRepositoryInterface {
    public function getModel()
    {
        return new Link;
    }
}
