<?php

namespace App;

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
        'from',
        'to',
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

    public function parse()
    {
        if($this->parsed_at) {
            echo $this->parsed_content;
            return;
        }

        $parser = new HtmlParser($this->content);

        $pixelID = $parser->setTrackingPixel();
        $linkIDs = $parser->setTrackingLinks();

        $this->uuid = $pixelID;
        $this->links()->createMany($linkIDs);
        $this->parsed_content = $parser->saveHTML();
        $this->parsed_at = new Carbon;

        $this->save();
    }
}
