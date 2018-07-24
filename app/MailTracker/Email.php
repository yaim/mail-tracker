<?php

namespace App\MailTracker;

use App\Exceptions\EmailNotParsedException;
use App\Exceptions\EmailAlreadySentException;
use App\MailTracker\Helpers\HtmlParser;
use App\MailTracker\Database\Eloquent\UuidModel as Model;
use Mail;

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

    public function isParsed()
    {
        return isset($this->parsed_at);
    }

    public function isSent()
    {
        return isset($this->sent_at);
    }

    public function send()
    {
        if (!$this->isParsed()) {
            throw new EmailNotParsedException();
        } elseif ($this->isSent()) {
            throw new EmailAlreadySentException();
        }

        $data = [
            'from'    => $this->from_email_address,
            'to'      => $this->to_email_address,
            'subject' => $this->subject,
            'content' => $this->parsed_content
        ];

        Mail::send([], [], function($message) use ($data) {
            $message->from($data['from']);
            $message->to($data['to']);
            $message->subject($data['subject']);
            $message->setBody($data['content'], 'text/html');
        });

        $this->sent_at = now();

        $this->save();

        return $this;
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
