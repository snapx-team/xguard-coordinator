<?php

namespace Tests\Routes;

use App\Models\JobSite;
use App\Models\JobSiteSubaddress;
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
    const JOB_SITE_ID = 'job_site_id';
    const ADDRESS = 'address';
    const FORMATTED_ADDRESS = 'formatted_address';
    const LONGITUDE = 'longitude';
    const LATITUDE = 'latitude';
    const ID = 'id';
    const INVALID_ID = 'invalid ID';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        Auth::setUser($this->user);
    }

    public function testCreateJobSiteVisitFromJobSite()
    {
        $jobSite = factory(JobSite::class)->create();
        $supervisorShift = factory(SupervisorShift::class)->create();
        $apiCall = route(self::CREATE_JOB_SITE_SHIFT_VISIT);
        $data = [
            JobSiteVisit::SUPERVISOR_SHIFT_ID => $supervisorShift->id,
            JobSiteVisit::ADDRESS_ID => $jobSite->id,
            JobSiteVisit::IS_PRIMARY_ADDRESS => true,
            JobSiteVisit::START_TIME => $this->faker->date(),
        ];
        $response = $this->post($apiCall, $data);
        $count = count(JobSiteVisit::all());
        $this->assertTrue($count == 1);
        $response->assertSuccessful()->assertJson([self::ID => 1]);
    }

    public function testCreateJobSiteVisitFromSubAddress()
    {
        $jobSite = factory(JobSite::class)->create();
        $jobSiteSubAddress = JobSiteSubaddress::create([
            self::JOB_SITE_ID => $jobSite->id,
            self::ADDRESS => $this->faker->address,
            self::FORMATTED_ADDRESS => $this->faker->address,
            self::LONGITUDE => $this->faker->longitude,
            self::LATITUDE => $this->faker->latitude,
        ]);
        $supervisorShift = factory(SupervisorShift::class)->create();
        $apiCall = route(self::CREATE_JOB_SITE_SHIFT_VISIT);
        $data = [
            JobSiteVisit::SUPERVISOR_SHIFT_ID => $supervisorShift->id,
            JobSiteVisit::ADDRESS_ID => $jobSiteSubAddress->id,
            JobSiteVisit::IS_PRIMARY_ADDRESS => false,
            JobSiteVisit::START_TIME => $this->faker->date(),
        ];
        $response = $this->post($apiCall, $data);
        $count = count(JobSiteVisit::all());
        $this->assertTrue($count == 1);
        $response->assertSuccessful()->assertJson([self::ID => 1]);
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
            JobSiteVisit::SUPERVISOR_SHIFT_ID => self::INVALID_ID,
            JobSiteVisit::ID => self::INVALID_ID,
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
