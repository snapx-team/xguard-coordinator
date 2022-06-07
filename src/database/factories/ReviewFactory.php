<?php

/** @var Factory $factory */

use App\Models\UserShift;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Xguard\Coordinator\Models\Review;

$factory->define(Review::class, function (Faker $faker, $attributes) {

    return [
        Review::USER_SHIFT_ID => factory(UserShift::class),
        Review::NOTE => $faker->text(rand(15, 300)),
        Review::RATING => $faker->numberBetween(0, 5),
    ];
});

