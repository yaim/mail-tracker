<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRawEmail;
use App\RawEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auth::user()->rawEmails;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRawEmail $request)
    {
        $email = new RawEmail;

        $email->fill($request->toArray());
        $email->user_id = Auth::user()->id;
        $email->save();

        return response($email, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RawEmail  $rawEmail
     * @return \Illuminate\Http\Response
     */
    public function show(RawEmail $email)
    {
        return $email;
    }
}
