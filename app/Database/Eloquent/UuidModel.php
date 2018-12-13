<?php

namespace App\Database\Eloquent;

use Ramsey\Uuid\Uuid;

abstract class UuidModel extends Model
{
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $keyName = $model->getKeyName();
            $model->$keyName = !isset($model->$keyName) ?  Uuid::uuid4()->toString() : $model->$keyName;
        });
    }
}
