<?php

namespace App\MailTracker\Repositories\Eloquent;

use App\MailTracker\Email;
use App\MailTracker\Link;
use App\MailTracker\Repositories\Contracts\LinkRepositoryInterface;
use App\MailTracker\Services\Contracts\Link\LinkCreatorInterface as LinkCreator;

class LinkRepository extends AbstractRepository implements LinkRepositoryInterface {

    protected $creator;

    public function __construct(LinkCreator $creator)
    {
        $this->creator = $creator;

        parent::__construct();
    }

    public function getModel()
    {
        return new Link;
    }

    public function createManyForEmail(Email $email, array $data)
    {
        return $this->creator->createMany($data, $email);
    }

}
