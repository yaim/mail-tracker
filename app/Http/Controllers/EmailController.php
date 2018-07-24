<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmail;
use App\MailTracker\Repositories\Contracts\EmailRepositoryInterface as EmailRepository;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    protected $emails; 

    public function __construct(EmailRepository $emails)
    {
        $this->emails = $emails;
    }

    public function index()
    {
        $emails = $this->emails->forUser(Auth::user());

        return response($emails);
    }

    public function store(StoreEmail $request)
    {
        $email = $this->emails->createForUser(Auth::user(), $request->toArray());

        return response($email, 201);
    }

    public function show(string $id)
    {
        $email = $this->emails->findOrFail($id);

        return response($email);
    }

    public function showParsed(string $id)
    {
        $email = $this->emails->findParsedOrFail($id);

        return response($email->parsed_content);
    }
}
