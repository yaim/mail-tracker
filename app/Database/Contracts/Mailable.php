<?php

namespace App\Database\Contracts;

interface Mailable
{
    public function getFrom();

    public function getTo();

    public function getSubject();

    public function getContent();
}
