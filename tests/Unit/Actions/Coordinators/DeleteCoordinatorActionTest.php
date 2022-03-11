<?php

namespace Tests\Unit\Actions\Coordinators;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Xguard\Coordinator\Actions\Coordinators\DeleteCoordinatorAction;
use Xguard\Coordinator\Models\Coordinator;

class DeleteCoordinatorActionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->coordinator = factory(Coordinator::class)->states('admin')->create(['user_id' => $this->user->id]);
        Auth::setUser($this->user);
        session(['role' => 'admin', 'coordinator_id' => $this->user->id]);
    }

    public function testDeletionOfCoordinator()
    {
        $newUser = factory(User::class)->create();
        $newCoordinator = factory(Coordinator::class)->states('admin')->create(['user_id' => $newUser->id]);

        app(DeleteCoordinatorAction::class)->fill(['coordinatorId' => $newCoordinator->id])->run();

        $this->assertNull(Coordinator::where('id', $newCoordinator->id)->first());
    }
}
