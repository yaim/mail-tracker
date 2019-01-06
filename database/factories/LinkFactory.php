<?php

use App\Link;
use Faker\Generator as Faker;

$factory->define(Link::class, function (Faker $faker) {
    return [
        'id'       => $faker->unique()->uuid,
        'email_id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
        'address'  => $faker->url,
    ];
});
