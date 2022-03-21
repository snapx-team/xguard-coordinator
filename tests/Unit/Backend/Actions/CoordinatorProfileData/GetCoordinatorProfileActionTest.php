<?php

namespace Tests\Unit\Actions\Users;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Xguard\Coordinator\Actions\CoordinatorProfileData\GetCoordinatorProfileAction;
use Xguard\Coordinator\Models\Coordinator;

class GetCoordinatorProfileActionTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        Auth::setUser($this->user);
        $this->coordinator = factory(Coordinator::class)->states('admin')->create(['user_id' => $this->user->id]);
        session(['role' => 'admin', 'coordinator_id' => $this->coordinator->id]);
    }

    public function testGetUserProfileAction()
    {
        $result = (new GetCoordinatorProfileAction)->run();
        $this->assertEquals($this->coordinator->user->full_name, $result['userName']);
        $this->assertEquals($this->coordinator->role, $result['userStatus']);
        $this->assertEquals(Carbon::parse($this->coordinator->created_at)->toDateString(), $result['userCreatedAt']);
        $this->assertEquals($this->coordinator->user->locale, $result['language']);
    }
}
