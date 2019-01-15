<?php

namespace App\Http\Controllers;

use App\Events\Emails\EmailCreated;
use App\Http\Requests\StoreEmail;
use App\Http\Resources\Email as EmailResource;
use App\Http\Resources\EmailCollection as EmailCollectionResource;
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

        return new EmailCollectionResource($emails);
    }

    public function store(StoreEmail $request)
    {
        $email = $this->emails->createForUser(Auth::user(), $request->toArray());

        event(new EmailCreated($email));

        return new EmailResource($email);
    }

    public function show(string $id)
    {
        $email = $this->emails->findOrFailForUser($id, Auth::user());

        return new EmailResource($email);
    }
}
