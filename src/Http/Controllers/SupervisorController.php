<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\JsonResponse;
use Illuminate\Http\Request;
use Xguard\Coordinator\Actions\Supervisors\GetSupervisorsDataAction;
use Xguard\Coordinator\Actions\Supervisors\GetUserShiftOdometerImagesAction;

class SupervisorController extends Controller
{
    const DATE_RANGE = 'dateRange';
    const USER_ID = 'userId';
    const SHIFT_ID = 'shiftId';

    public function getSupervisorsData(Request $request)
    {
        $dateRange = $request->all();
        try {
            return app(GetSupervisorsDataAction::class)->fill([self::DATE_RANGE => $dateRange])->run();
        } catch (\Exception $e) {
            return new JsonResponse([], $e->getCode(), $e->getMessage());
        }
    }

    public function getUserShiftOdometerImages($userId, $shiftId)
    {
        return app(GetUserShiftOdometerImagesAction::class)->fill([
            self::USER_ID => $userId, self::SHIFT_ID => $shiftId
        ])->run();
    }
}
