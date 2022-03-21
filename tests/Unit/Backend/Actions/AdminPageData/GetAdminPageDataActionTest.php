<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Xguard\Coordinator\Actions\AdminPageData\GetAdminPageDataAction;
use Xguard\Coordinator\Models\Coordinator;

class GetAdminPageDataActionTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $user = factory(User::class)->create();
        Auth::setUser($user);
        $coordinator = factory(Coordinator::class)->states('admin')->create(['user_id' => $user->id,]);
        session(['role' => 'admin', 'coordinator_id' => $coordinator->id]);

        factory(Coordinator::class, 2)->create();
    }

    public function testGetAdminPageDataIfAdmin()
    {
        $dashboardData = app(GetAdminPageDataAction::class)->run();
        $this->assertCount(3, $dashboardData['coordinators']);
    }

    public function testGetAdminPageDataActionThrowsErrorIfCoordinatorIsNotAdmin()
    {
        $this->expectException(AuthorizationException::class);
        session(['role' => 'coordinator', 'coordinator_id' => session('coordinator_id')]);
        app(GetAdminPageDataAction::class)->run();
    }
}
