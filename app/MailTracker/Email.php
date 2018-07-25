<?php

namespace App\MailTracker;

use App\MailTracker\Database\Contracts\Clickable;
use App\MailTracker\Database\Eloquent\UuidModel as Model;

class Email extends Model implements Clickable
{
    protected $dates = [
        'parsed_at',
        'sent_at'
    ];

    protected $fillable = [
        'from_email_address',
        'to_email_address',
        'subject',
        'content',
        'tags',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function clicks()
    {
        return $this->morphMany(Click::class, 'clickable');
    }
}
