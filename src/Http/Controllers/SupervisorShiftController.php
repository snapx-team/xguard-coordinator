<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response as ResponseAlias;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use Xguard\Coordinator\Http\Requests\SupervisorShiftPatchRequest;
use Xguard\Coordinator\Http\Requests\SupervisorShiftPostRequest;
use Xguard\Coordinator\Models\Odometer;
use Xguard\Coordinator\Models\SupervisorShift;

/**
 * Class SupervisorShiftController
 * @package Xguard\Coordinator\Http\Controllers
 * @group Coordinator Plugin
 */
class SupervisorShiftController extends Controller
{
    const ODOMETER_ID = 'odometer_id';

    /**
     * Create Supervisor Shift
     * @response 201 {"id": 1}
     * @response 400 {"message": "Error message description"}
     */
    public function store(SupervisorShiftPostRequest $request)
    {
        try {
            $shift = SupervisorShift::create([
                SupervisorShift::USER_ID => $request->user_id,
                SupervisorShift::START_TIME => Date::now(),
                SupervisorShift::START_LAT => $request->start_lat,
                SupervisorShift::START_LNG => $request->start_lng
            ]);
            return response([SupervisorShift::ID => $shift->id], ResponseAlias::HTTP_CREATED);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update Supervisor Shift
     * @response 200 {"id": 1}
     * @response 400 {"message": "Error message description"}
     */
    public function update(SupervisorShiftPatchRequest $request)
    {
        $shift = SupervisorShift::find($request->id);
        $shift->fill([
            SupervisorShift::END_TIME => Date::now(),
            SupervisorShift::END_LAT => $request->end_lat,
            SupervisorShift::END_LNG => $request->end_lng
        ])->save();
        $shift->refresh();
        return response([SupervisorShift::ID => $shift->id], ResponseAlias::HTTP_OK);
    }

    /**
     * Check and returns if a previous shift was completed
     * @response 200 {"id": 1}
     * @response 400 {"message": "Error message description"}
     */
    public function checkPreviousShiftCompleted($userId)
    {
        try {
            $lastShift = SupervisorShift::where('user_id', $userId)->latest()->first();
            if ($lastShift && !$lastShift->end_time) {
                return response([SupervisorShift::ID => $lastShift->id, self::ODOMETER_ID => $lastShift->odometer->id], ResponseAlias::HTTP_OK);
            }
            Log::debug($lastShift);


            return response([SupervisorShift::ID => null, self::ODOMETER_ID => null], ResponseAlias::HTTP_OK);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }
}
