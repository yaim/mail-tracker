<?php

namespace App\Services\Email;

use App\Events\Emails\EmailParsed;
use App\Email;
use App\Repositories\Contracts\LinkRepositoryInterface as LinkRepository;
use App\Services\Contracts\Email\EmailParserInterface;
use App\Services\Contracts\Email\EmailValidatorInterface as EmailValidator;
use Ramsey\Uuid\Uuid;

class EmailParser implements EmailParserInterface
{
    protected $domParser;
    protected $email;
    protected $links;
    protected $validator;

    public function __construct(EmailValidator $validator, LinkRepository $links)
    {
        $this->validator = $validator;
        $this->links = $links;
    }

    public function parse(Email $email)
    {
        $this->email = $email;
        $this->domParser = domDocument($this->email->content);

        $this->validate();
        $this->process();

        event(new EmailParsed($this->email));
    }

    protected function validate()
    {
        $this->validator
             ->setModel($this->email)
             ->checkNotParsed();
    }

    protected function process()
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

        $table = $this->domParser->getElementsByTagName('table')->item(0)
              ?? $this->domParser->childNodes->item(0);
        $table->parentNode->insertBefore($pixel, $table);
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

        $this->links->createManyForEmail($this->email, $links);
    }

}
