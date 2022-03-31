<?php

/** @var Factory $factory */

use App\Models\JobSite;
use Faker\Generator as Faker;
use Xguard\Coordinator\Models\JobSiteVisit;
use Xguard\Coordinator\Models\SupervisorShift;
use Illuminate\Database\Eloquent\Factory;

$factory->define(JobSiteVisit::class, function (Faker $faker, $attributes) {

    $supervisorShift = SupervisorShift::find(Arr::get($attributes, JobSiteVisit::SUPERVISOR_SHIFT_ID, -1))
        ?? factory(SupervisorShift::class)->create();

    $jobSite = JobSite::find(Arr::get($attributes, JobSiteVisit::JOB_SITE_ID, -1))
        ?? factory(JobSite::class)->create();

    $start = $faker->dateTimeBetween('-30 days', '+0 days');
    $end = $faker->dateTime($start->format('Y-m-md H:i:s') . '+3 hour');

    return [
        JobSiteVisit::SUPERVISOR_SHIFT_ID => $supervisorShift->id,
        JobSiteVisit::JOB_SITE_ID => $jobSite->id,
        JobSiteVisit::START_TIME => $start,
        JobSiteVisit::END_TIME => $end
    ];
});
