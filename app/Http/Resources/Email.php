<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Email extends Resource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'subject'        => $this->subject,
            'from'           => $this->from_email_address,
            'to'             => $this->to_email_address,
            'tags'           => $this->tags,
            'content'        => $this->content,
            'parsed_content' => $this->parsed_content,
            'created_at'     => (string) $this->created_at,
            'parsed_at'      => (string) $this->parsed_at,
            'sent_at'        => (string) $this->sent_at,
        ];
    }

    public function parsedContent()
    {
        return $this->parsed_content;
    }
}
