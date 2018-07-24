<?php

namespace App\MailTracker\Repositories\Contracts;

interface AbstractRepositoryInterface {

    public function getModel();

    public function findOrFail(string $id);
}
