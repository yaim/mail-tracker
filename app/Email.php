<?php

namespace App;

use App\Collections\EmailCollection;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from_email_address',
        'to_email_address',
        'subject',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function newCollection(array $emails = [])
    {
        return new EmailCollection($emails);
    }

    public function clicks()
    {
        return $this->morphMany(Click::class, 'clickable');
    }

    public function parse()
    {
        if($this->parsed_at) {
            return $this;
        }

        $parser = new HtmlParser($this->content);

        $pixelID = $parser->setTrackingPixel();
        $linkIDs = $parser->setTrackingLinks();

        $this->uuid = $pixelID;
        $this->links()->createMany($linkIDs);

        $this->parsed_at = new Carbon;
        $this->parsed_content = $parser->saveHTML();

        $this->save();

        return $this;
    }
}
