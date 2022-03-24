<?php

/** @var Factory $factory */

use App\Models\User;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Xguard\Coordinator\Models\SupervisorShift;

$factory->define(SupervisorShift::class, function (Faker $faker, $attributes) {

    $user = User::find(Arr::get($attributes, 'id', -1))
        ?? factory(User::class)->create();
    return [
        'user_id' => $user->id,
        'start_time' => Carbon::now(config('global.appTimezone'))
    ];
});
