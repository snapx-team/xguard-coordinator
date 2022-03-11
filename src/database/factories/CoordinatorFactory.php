<?php

/** @var Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Xguard\Coordinator\Models\Coordinator;

$factory->define(Coordinator::class, function (Faker $faker) {

    return [
        'user_id' => factory(User::class),
        'role' => 'employee'
    ];
});

$factory->state(Coordinator::class, 'admin', function (Faker $faker) {
    return [
        'role' => 'admin',
    ];
});
