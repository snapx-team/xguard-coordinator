<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Xguard\Coordinator\Http\Requests\OdometerRequest;
use Xguard\Coordinator\Models\Odometer;

class OdometerController extends Controller
{

    public function store(OdometerRequest $request): \Illuminate\Http\Response
    {
        $odometer = Odometer::create([
            Odometer::SUPERVISOR_SHIFT_ID => $request->supervisor_shift_id,
            Odometer::START_ODOMETER => $request->start_odometer
        ]);
        return Response::make([Odometer::ID => $odometer->id], ResponseAlias::HTTP_CREATED);
    }

    public function update(OdometerRequest $request): \Illuminate\Http\Response
    {
        $odometer = Odometer::find($request->id);
        $odometer->fill([Odometer::END_ODOMETER => $request->end_odometer])->save();
        $odometer->refresh();
        return Response::make([Odometer::ID => $odometer->id], ResponseAlias::HTTP_OK);
    }
}
