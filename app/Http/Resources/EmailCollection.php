<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EmailCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function ($email) {
            return [
                'id'         => $email->id,
                'subject'    => $email->subject,
                'from'       => $email->from_email_address,
                'to'         => $email->to_email_address,
                'created_at' => (string) $email->created_at,
                'parsed_at'  => (string) $email->parsed_at,
                'sent_at'    => (string) $email->sent_at,
            ];
        });
    }
}
