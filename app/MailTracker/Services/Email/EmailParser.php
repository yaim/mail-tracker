<?php

namespace App\MailTracker\Services\Email;

use App\Events\EmailParsed;
use App\Exceptions\EmailAlreadyParsedException;
use App\MailTracker\Email;
use App\MailTracker\Services\Contracts\Email\EmailParserInterface;
use Ramsey\Uuid\Uuid;

class EmailParser implements EmailParserInterface
{
    protected $domParser;
    protected $email;

    public function process(Email $email)
    {
        $this->email = $email;
        $this->domParser = domDocument($this->email->content);

        $this->validate();
        $this->parse();

        event(new EmailParsed($this->email));
    }

    protected function parse()
    {
        $this->setTrackingPixel($this->email->id);
        $this->setTrackingLinks();

        $this->email->parsed_content = $this->domParser->saveHTML();
        $this->email->parsed_at = now();

        $this->email->save();
    }

    protected function setTrackingPixel(string $uuid)
    {
        $pixel = $this->domParser->createElement("img");
        $pixel->setAttribute('src', route('tracking.email', $uuid));

        $this->domParser->appendChild($pixel);
    }

    protected function setTrackingLinks()
    {
        $links = [];

        foreach($this->domParser->getElementsByTagName('a') as $dom) {
            $uuid = Uuid::uuid4()->toString();

            $links[] = [
                'address' => $dom->getAttribute('href'),
                'id'      => $uuid
            ];

            $dom->setAttribute('href', route('tracking.links', $uuid));
        }

        $this->email->links()->createMany($links);
    }

    protected function validate()
    {
        $this->checkNotParsed();

        return true;
    }

    protected function checkNotParsed()
    {
        if (isset($this->email->parsed_at)) {
            throw new EmailAlreadyParsedException();
        }

        return true;
    }

}
