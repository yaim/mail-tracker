<?php

namespace App\MailTracker\Helpers;

use DOMDocument;
use Ramsey\Uuid\Uuid;

class HtmlParser
{
    protected $document;

    public function __construct($html)
    {
        $this->document = new DOMDocument('1.0','UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $this->document->loadHTML($html);
        libxml_use_internal_errors($internalErrors);

        return $this;
    }

    public function setTrackingPixel(string $uuid)
    {
        $pixel = $this->document->createElement("img");
        $pixel->setAttribute('src', url('tracking/email/'.$uuid.'.gif'));

        $this->document->appendChild($pixel);

        return true;
    }

    public function setTrackingLinks()
    {
        $links = [];

        foreach($this->document->getElementsByTagName('a') as $dom) {
            $uuid = Uuid::uuid4()->toString();

            $link = [
                'address' => $dom->getAttribute('href'),
                'id'      => $uuid
            ];

            $links[] = $link;

            $dom->setAttribute('href', url('tracking/links/'.$uuid));
        }

        return $links;
    }

    public function saveHTML()
    {
        return $this->document->saveHTML();
    }
}