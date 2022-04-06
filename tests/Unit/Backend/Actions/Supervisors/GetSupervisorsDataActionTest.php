<?php

namespace Tests\Unit\Actions\Coordinators;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Xguard\Coordinator\Actions\Supervisors\GetSupervisorsDataAction;
use Xguard\Coordinator\Models\Coordinator;
use Tests\SetsRolesAndPermissions;

class GetSupervisorsDataActionTest extends TestCase
{
    use RefreshDatabase, SetsRolesAndPermissions;

    public function setUp(): void
    {
        parent::setUp();
        $this->setRolesAndPermissions();
        $this->user = factory(User::class)->create();
        $this->coordinator = factory(Coordinator::class)->states('admin')->create(['user_id' => $this->user->id]);
        Auth::setUser($this->user);
        session(['role' => 'admin', 'coordinator_id' => $this->user->id]);
    }

    public function testRetrieveOnlySupervisors()
    {
        $newUser = factory(User::class)->create();

        $supervisorsData = app(GetSupervisorsDataAction::class)->run();
        $this->assertFalse($supervisorsData['supervisorsData']->contains('id', $newUser->id));

        $newUser->assignRole(Roles::SUPERVISOR()->getValue());
        $supervisorsData = app(GetSupervisorsDataAction::class)->run();
        $this->assertTrue($supervisorsData['supervisorsData']->contains('id', $newUser->id));
    }
}
