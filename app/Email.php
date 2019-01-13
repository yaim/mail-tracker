<?php

namespace App;

use App\Database\Contracts\Clickable;
use App\Database\Contracts\Mailable;
use App\Database\Eloquent\UuidModel as Model;

class Email extends Model implements Clickable, Mailable
{
    protected $dates = [
        'parsed_at',
        'sent_at',
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

    public function getFrom()
    {
        return $this->from_email_address;
    }

    public function getTo()
    {
        return $this->to_email_address;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getContent()
    {
        return $this->parsed_content;
    }
}
