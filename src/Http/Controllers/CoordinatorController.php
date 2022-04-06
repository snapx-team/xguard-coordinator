<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Xguard\Coordinator\Models\Coordinator;
use Xguard\Coordinator\Actions\Coordinators\CreateOrUpdateCoordinatorAction;
use Xguard\Coordinator\Actions\Coordinators\DeleteCoordinatorAction;

class CoordinatorController extends Controller
{
    public function createCoordinators(Request $request)
    {
        $coordinatorData = $request->all();

        try {
            app(CreateOrUpdateCoordinatorAction::class)->fill([
                "selectedUsers" => $coordinatorData['selectedUsers'],
                "role" => $coordinatorData['role'],
            ])->run();
        } catch (\Exception $e) {
            return response([
                'success' => 'false',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function deleteCoordinator($id)
    {
        try {
            app(DeleteCoordinatorAction::class)->fill([
                'coordinatorId' => $id
            ])->run();
        } catch (\Exception $e) {
            return response([
                'success' => 'false',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getCoordinators()
    {
        return Coordinator::with('user')->get();
    }
}
