<?php

namespace App\Http\Controllers;

use App\Events\Emails\EmailCreated;
use App\Http\Resources\Email;
use App\Http\Resources\EmailCollection;
use App\Http\Requests\StoreEmail;
use App\Repositories\Contracts\EmailRepositoryInterface as EmailRepository;
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

        return new EmailCollection($emails);
    }

    public function store(StoreEmail $request)
    {
        $email = $this->emails->createForUser(Auth::user(), $request->toArray());

        event(new EmailCreated($email));

        return new Email($email);
    }

    public function show(string $id)
    {
        $email = $this->emails->findOrFailForUser($id, Auth::user());

        return new Email($email);
    }

    public function showParsed(string $id)
    {
        $email = $this->emails->findParsedOrFail($id);

        return (new Email($email))->parsedContent();
    }
}
