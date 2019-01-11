<?php

use App\Email;
use App\Link;
use Faker\Generator as Faker;

$factory->define(Link::class, function (Faker $faker) {
    return [
        'id'       => $faker->unique()->uuid,
        'email_id' => factory(Email::class)->create()->id,
        'address'  => $faker->url,
    ];
});
