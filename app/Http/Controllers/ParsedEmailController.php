<?php

namespace App\Http\Controllers;

use App\Http\Resources\Email as EmailResource;
use App\Repositories\Contracts\EmailRepositoryInterface as EmailRepository;

class ParsedEmailController extends Controller
{
    protected $emails;

    public function __construct(EmailRepository $emails)
    {
        $this->emails = $emails;
    }

    public function show(string $id)
    {
        $email = $this->emails->findParsedOrFail($id);

        return (new EmailResource($email))->parsedContent();
    }
}
