<?php

namespace Tests\Unit\Actions\Coordinators;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Xguard\Coordinator\Actions\Coordinators\CreateOrUpdateCoordinatorAction;
use Xguard\Coordinator\Models\Coordinator;

class CreateOrUpdateCoordinatorActionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $user = factory(User::class)->create();
        $coordinator = factory(Coordinator::class)->states('admin')->create(['user_id' => $user->id,]);
        Auth::setUser($user);
        session(['role' => 'admin', 'coordinator_id' => $coordinator->id]);
    }

    public function testCreateOrUpdateCoordinatorActionTest()
    {
        $users = factory(User::class, 2)->create();
        factory(Coordinator::class)->states('admin')->create(['user_id' => $users[0]->id]);
        app(CreateOrUpdateCoordinatorAction::class)->fill([
            'selectedUsers' => [
                [
                    'id' => $users[0]->id,
                ],
                [
                    'id' => $users[1]->id,
                ]
            ],
            'role' => 'admin'
        ])->run();

        $this->assertDatabaseHas('sa_coordinators', ['user_id' => $users[0]->id]);
        $this->assertDatabaseHas('sa_coordinators', ['user_id' => $users[1]->id]);
    }
}
