<?php

namespace App\Repositories\Contracts;

interface AbstractRepositoryInterface {

    public function getModel();

    public function create(array $data);

    public function findOrFail(string $id);
}
