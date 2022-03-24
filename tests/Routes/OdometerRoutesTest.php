<?php

namespace Tests\Routes;

use App\Enums\Roles;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\SetsAdminUser;
use Tests\SetsRolesAndPermissions;
use Tests\TestCase;
use Xguard\Coordinator\Models\Odometer;
use Xguard\Coordinator\Models\SupervisorShift;

/**
 * @group supervisor
 */

class OdometerRoutesTest extends TestCase
{
    use RefreshDatabase, SetsRolesAndPermissions, SetsAdminUser;

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
            'supervisor_shift_id' => $supervisorShift->id,
            'start_odometer' => '98239',
        ];
        $response = $this->post($apiCall, $data);
        $count = count(Odometer::all());
        $this->assertTrue($count == 1);
        $response->assertSuccessful()->assertJson(
            [
                'id' => 1
            ]
        );
    }

    public function testUpdateSupervisorOdometer()
    {
        $odometer = factory(Odometer::class)->create();
        $apiCall = route(self::SUPERVISOR_UPDATE_ODOMETER, $odometer->id);

        $start_odometer = $odometer->start_odometer;
        $end_odometer = $start_odometer + 500;
        $data = [
            Odometer::END_ODOMETER => $end_odometer
        ];
        $response = $this->patch($apiCall, $data);
        $response->assertOk();
    }
}
