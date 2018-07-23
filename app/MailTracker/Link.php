<?php

namespace App\MailTracker;

use App\MailTracker\Database\Eloquent\UuidModel as Model;

class Link extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'address',
    ];

    public function email()
    {
    	return $this->belongsTo(Email::class);
    }

    public function clicks()
    {
        return $this->morphMany(Click::class, 'clickable');
    }
}