<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RawEmail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from',
        'to',
        'subject',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
