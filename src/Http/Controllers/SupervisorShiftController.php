<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response as ResponseAlias;
use Xguard\Coordinator\Http\Requests\SupervisorShiftPatchRequest;
use Xguard\Coordinator\Http\Requests\SupervisorShiftPostRequest;
use Xguard\Coordinator\Models\SupervisorShift;

/**
 * Class SupervisorShiftController
 * @package Xguard\Coordinator\Http\Controllers
 * @group Coordinator Plugin
 */
class SupervisorShiftController extends Controller
{
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
                SupervisorShift::START_TIME => $request->start_time,
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
            SupervisorShift::END_TIME => $request->end_time,
            SupervisorShift::END_LAT => $request->end_lat,
            SupervisorShift::END_LNG => $request->end_lng
        ])->save();
        $shift->refresh();
        return response([SupervisorShift::ID => $shift->id], ResponseAlias::HTTP_OK);
    }
}
