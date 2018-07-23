<?php

namespace App\MailTracker;

use App\MailTracker\Helpers\HtmlParser;
use App\MailTracker\Database\Eloquent\UuidModel as Model;

class Email extends Model
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

    public function parse()
    {
        if ($this->isParsed()) {
            return $this;
        }

        $parser = new HtmlParser($this->content);

        $parser->setTrackingPixel($this->id);
        $linkIDs = $parser->setTrackingLinks();

        $this->links()->createMany($linkIDs);

        $this->parsed_at = now();
        $this->parsed_content = $parser->saveHTML();

        $this->save();

        return $this;
    }
}
