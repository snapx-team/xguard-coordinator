<?php

namespace Tests\Routes;

use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\SetsAdminUser;
use Tests\SetsRolesAndPermissions;
use Tests\TestCase;
use Xguard\Coordinator\Models\SupervisorShift;

/**
 * @group supervisor
 */
class SupervisorShiftRoutesTest extends TestCase
{
    use RefreshDatabase, SetsRolesAndPermissions, SetsAdminUser;

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
            'user_id' => $user->id,
            'start_time' => '2022-02-01 14:31:01',
        ];
        $response = $this->post($apiCall, $data);
        $count = count(SupervisorShift::all());
        $this->assertTrue($count == 1);
        $response->assertSuccessful()->assertJson(['id' => 1]);
    }

    public function testUpdateSupervisorShift()
    {
        $supervisorShift = factory(SupervisorShift::class)->create();
        $apiCall = route(self::COORDINATOR_UPDATE_SHIFT, $supervisorShift->id);
        $data = [
            'end_time' => Carbon::now(config('global.appTimezone'))
        ];
        $response = $this->patch($apiCall, $data);
        $response->assertOk();
    }
}
