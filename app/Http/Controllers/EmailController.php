<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmail;
use App\Email;
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
        return Auth::user()->emails;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmail $request)
    {
        $email = new Email;

        $email->fill($request->toArray());
        $email->user_id = Auth::user()->id;
        $email->save();

        return response($email, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function show(Email $email)
    {
        return $email;
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function showHtml(string $id)
    {
        $email = Email::findOrFail($id);

        if(!$email->isParsed()) {
            return abort(404);
        }

        return $email->parsed_content;
    }
}
