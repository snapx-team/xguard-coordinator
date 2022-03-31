<?php

namespace Tests\Routes;

use App\Models\User;
use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Xguard\Coordinator\Models\Odometer;
use Xguard\Coordinator\Models\SupervisorShift;

/**
 * @group supervisor
 */

class OdometerRoutesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const SUPERVISOR_CREATE_ODOMETER = 'coordinator.create-odometer';
    const SUPERVISOR_UPDATE_ODOMETER = 'coordinator.update-odometer';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        Auth::setUser($this->user);
    }

    public function testCreateSupervisorOdometer()
    {
        $supervisorShift = factory(SupervisorShift::class)->create();
        $apiCall = route(self::SUPERVISOR_CREATE_ODOMETER);
        $data = [
            Odometer::SUPERVISOR_SHIFT_ID => $supervisorShift->id,
            Odometer::START_ODOMETER => $this->faker->numberBetween(0, 500000)
        ];
        $response = $this->post($apiCall, $data);
        $count = count(Odometer::all());
        $this->assertTrue($count == 1);
        $response->assertSuccessful()->assertJson(['id' => 1]);
    }

    public function testUpdateSupervisorOdometer()
    {
        $odometer = factory(Odometer::class)->create();
        $apiCall = route(self::SUPERVISOR_UPDATE_ODOMETER);

        $start_odometer = $odometer->start_odometer;
        $end_odometer = $start_odometer + 500;
        $data = [
            Odometer::ID => $odometer->id,
            Odometer::END_ODOMETER => $end_odometer
        ];
        $response = $this->patch($apiCall, $data);
        $response->assertOk();
    }

    public function testValidSupervisorShiftIdValidations()
    {
        $apiCall = route(self::SUPERVISOR_CREATE_ODOMETER);
        $data = [
            Odometer::START_ODOMETER => $this->faker->numberBetween(0, 500000)
        ];
        $response = $this->post($apiCall, $data);
        $response->assertJsonValidationErrors([Odometer::SUPERVISOR_SHIFT_ID]);
    }

    public function testStartOdometerOrEndOdometerTimeIsRequiredValidations()
    {
        $apiCall = route(self::SUPERVISOR_UPDATE_ODOMETER);
        $response = $this->patch($apiCall, []);
        $response->assertJsonValidationErrors([Odometer::START_ODOMETER, Odometer::END_ODOMETER]);
    }
}
