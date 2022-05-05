<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response as ResponseAlias;
use Xguard\Coordinator\Http\Requests\OdometerPatchRequest;
use Xguard\Coordinator\Http\Requests\OdometerPostRequest;
use Xguard\Coordinator\Models\Odometer;

/**
 * Class OdometerController
 * @package Xguard\Coordinator\Http\Controllers
 * @group Coordinator Plugin
 */

class OdometerController extends Controller
{
    /**
     * Create Odometer Entry
     * @response 201 {"id": 1}
     * @response 400 {"message": "Error message description"}
     */
    public function store(OdometerPostRequest $request)
    {
        $odometer = Odometer::create([
            Odometer::SUPERVISOR_SHIFT_ID => $request->supervisor_shift_id,
            Odometer::START_ODOMETER => $request->start_odometer
        ]);
        return response([Odometer::ID => $odometer->id], ResponseAlias::HTTP_CREATED);
    }

    /**
     * Update Odometer Entry
     * @response 200 {"id": 1}
     * @response 400 {"message": "Error message description"}
     */
    public function update(OdometerPatchRequest $request)
    {
        $odometer = Odometer::find($request->id);
        $odometer->fill([Odometer::END_ODOMETER => $request->end_odometer])->save();
        $odometer->refresh();
        return response([Odometer::ID => $odometer->id], ResponseAlias::HTTP_OK);
    }
}
