<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\AbstractRepositoryInterface;

abstract class AbstractRepository implements AbstractRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->getModel();
    }

    public function first()
    {
        return $this->model->first();
    }

    public function findOrFail(string $id)
    {
        return $this->model->findOrFail($id);
    }

    public function count()
    {
        return $this->model->count();
    }
}
