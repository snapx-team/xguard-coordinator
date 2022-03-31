<?php

namespace Tests\Routes;

use App\Models\JobSite;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Xguard\Coordinator\Models\JobSiteVisit;
use Xguard\Coordinator\Models\SupervisorShift;

/**
 * @group supervisor
 */
class JobSiteVisitRoutesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const CREATE_JOB_SITE_SHIFT_VISIT = 'coordinator.create-job-site-visit';
    const UPDATE_JOB_SITE_SHIFT_VISIT = 'coordinator.update-job-site-visit';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        Auth::setUser($this->user);
    }

    public function testCreateJobSiteVisit()
    {
        $jobSite = factory(JobSite::class)->create();
        $supervisorShift = factory(SupervisorShift::class)->create();
        $apiCall = route(self::CREATE_JOB_SITE_SHIFT_VISIT);
        $data = [
            JobSiteVisit::SUPERVISOR_SHIFT_ID => $supervisorShift->id,
            JobSiteVisit::JOB_SITE_ID => $jobSite->id,
            JobSiteVisit::START_TIME => $this->faker->date(),
        ];
        $response = $this->post($apiCall, $data);
        $count = count(JobSiteVisit::all());
        $this->assertTrue($count == 1);
        $response->assertSuccessful()->assertJson(['id' => 1]);
    }

    public function testUpdateJobSiteVisit()
    {
        $jobSiteVisit = factory(JobSiteVisit::class)->create();
        $apiCall = route(self::UPDATE_JOB_SITE_SHIFT_VISIT);
        $data = [
            JobSiteVisit::ID => $jobSiteVisit->id,
            JobSiteVisit::END_TIME => $this->faker->date(),
        ];
        $response = $this->patch($apiCall, $data);
        $response->assertOk();
    }

    public function testValidSupervisorShiftIdAndJobSiteShiftIdValidations()
    {
        $apiCall = route(self::CREATE_JOB_SITE_SHIFT_VISIT);
        $data = [
            JobSiteVisit::SUPERVISOR_SHIFT_ID => 'invalid ID',
            JobSiteVisit::ID => 'invalid ID',
            JobSiteVisit::END_TIME => $this->faker->date(),
        ];
        $response = $this->patch($apiCall, $data);
        $response->assertJsonValidationErrors([JobSiteVisit::SUPERVISOR_SHIFT_ID, JobSiteVisit::ID]);
    }

    public function testTimeStartOrTimeEndIsRequiredValidations()
    {
        $jobSiteVisit = factory(JobSiteVisit::class)->create();
        $apiCall = route(self::UPDATE_JOB_SITE_SHIFT_VISIT);
        $response = $this->patch($apiCall, [JobSiteVisit::ID => $jobSiteVisit->id]);
        $response->assertJsonValidationErrors([JobSiteVisit::START_TIME, JobSiteVisit::END_TIME]);
    }
}
