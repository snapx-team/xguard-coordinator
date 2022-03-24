<?php

/** @var Factory $factory */

use Faker\Generator as Faker;
use Xguard\Coordinator\Models\Odometer;
use Xguard\Coordinator\Models\SupervisorShift;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Odometer::class, function (Faker $faker, $attributes) {

    $supervisorShift = SupervisorShift::find(Arr::get($attributes, 'id', -1))
        ?? factory(SupervisorShift::class)->create();

    return [
        Odometer::SUPERVISOR_SHIFT_ID => $supervisorShift->id,
        Odometer::START_ODOMETER => $faker->numberBetween(0, 500000)
    ];
});
