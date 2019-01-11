<?php

use App\Email;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Email::class, function (Faker $faker) {
    return [
        'id'                 => $faker->unique()->uuid,
        'from_email_address' => $faker->safeEmail,
        'to_email_address'   => $faker->safeEmail,
        'subject'            => $faker->text,
        'content'            => $faker->randomHTML,
        'user_id'            => factory(User::class)->create()->id,
    ];
});

$factory->state(Email::class, 'raw', [
    'parsed_content' => null,
    'parsed_at'      => null,
]);

$factory->state(Email::class, 'parsed', function (Faker $faker) {
    $content = $faker->randomHTML;
    $id = $faker->unique()->uuid;

    return [
        'id'             => $id,
        'content'        => $content,
        'parsed_content' => '<img src="'.route('tracking.email', $id).'">'.$content,
        'parsed_at'      => Carbon::parse('-1 hours'),
    ];
});
