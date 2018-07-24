<?php

namespace App\MailTracker\Repositories\Eloquent;

use App\MailTracker\Repositories\Contracts\AbstractRepositoryInterface;

abstract class AbstractRepository implements AbstractRepositoryInterface {
    protected $model;

    public function __construct()
    {
        $this->model = $this->getModel();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function findOrFail(string $id)
    {
        return $this->model->findOrFail($id);
    }

}
