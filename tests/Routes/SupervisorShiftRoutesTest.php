<?php

namespace Tests\Routes;

use App\Models\User;
use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Xguard\Coordinator\Models\SupervisorShift;

/**
 * @group supervisor
 */
class SupervisorShiftRoutesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const COORDINATOR_CREATE_SHIFT = 'coordinator.create-shift';
    const COORDINATOR_UPDATE_SHIFT = 'coordinator.update-shift';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        Auth::setUser($this->user);
    }

    public function testCreateSupervisorShift()
    {
        $user = factory(User::class)->states(['verified', 'employee'])->create();
        $apiCall = route(self::COORDINATOR_CREATE_SHIFT);
        $data = [
            SupervisorShift::USER_ID => $user->id,
            SupervisorShift::START_TIME => $this->faker->date(),
        ];
        $response = $this->post($apiCall, $data);
        $count = count(SupervisorShift::all());
        $this->assertTrue($count == 1);
        $response->assertSuccessful()->assertJson(['id' => 1]);
    }

    public function testUpdateSupervisorShift()
    {
        $supervisorShift = factory(SupervisorShift::class)->create();
        $apiCall = route(self::COORDINATOR_UPDATE_SHIFT);
        $data = [
            SupervisorShift::ID => $supervisorShift->id,
            SupervisorShift::END_TIME => $this->faker->date()
        ];
        $response = $this->patch($apiCall, $data);
        $response->assertOk();
    }

    public function testStartTimeIsRequiredIfUserIdIsPresentValidations()
    {
        $user = factory(User::class)->states(['verified', 'employee'])->create();
        $apiCall = route(self::COORDINATOR_CREATE_SHIFT);

        $data = [
            SupervisorShift::USER_ID => $user->id,
        ];
        $response = $this->post($apiCall, $data);
        $response->assertJsonValidationErrors([SupervisorShift::START_TIME]);
    }

    public function testUserIdMustBeValidValidation()
    {
        $apiCall = route(self::COORDINATOR_CREATE_SHIFT);
        $data = [
            SupervisorShift::USER_ID => 'invalid user id',
            SupervisorShift::START_TIME => $this->faker->date()
        ];
        $response = $this->post($apiCall, $data);
        $response->assertJsonValidationErrors([SupervisorShift::USER_ID]);
    }

    public function testStartOrEndTimeIsRequiredValidations()
    {
        $apiCall = route(self::COORDINATOR_UPDATE_SHIFT);
        $response = $this->patch($apiCall, []);
        $response->assertJsonValidationErrors([SupervisorShift::START_TIME, SupervisorShift::END_TIME]);
    }
}
