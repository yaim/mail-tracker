<?php

namespace App\Repositories\Contracts;

interface AbstractRepositoryInterface
{
    public function getModel();

    public function findOrFail(string $id);

    public function count();
}
