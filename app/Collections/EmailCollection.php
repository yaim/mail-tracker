<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class EmailCollection extends Collection
{
    public function parse()
    {
        $this->each(function($email) {
        	$email->parse();
        });
    }

    public function send()
    {
        $this->each(function($email) {
            $email->send();
        });
    }
}