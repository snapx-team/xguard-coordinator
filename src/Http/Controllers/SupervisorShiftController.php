<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Xguard\Coordinator\Http\Requests\SupervisorShiftRequest;
use Xguard\Coordinator\Models\SupervisorShift;

class SupervisorShiftController extends Controller
{
    public function store(SupervisorShiftRequest $request): \Illuminate\Http\Response
    {
        try {
            $shift = SupervisorShift::create([
                SupervisorShift::USER_ID => $request->user()->id,
                SupervisorShift::START_TIME => $request->start_time
            ]);
            return Response::make([SupervisorShift::ID => $shift->id], ResponseAlias::HTTP_CREATED);
        } catch (\Exception $e) {
            return response([
                'success' => 'false',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(SupervisorShiftRequest $request): \Illuminate\Http\Response
    {
        $shift = SupervisorShift::find($request->id);
        $shift->fill([SupervisorShift::END_TIME => $request->end_time,])->save();
        $shift->refresh();
        return Response::make([SupervisorShift::ID => $shift->id], ResponseAlias::HTTP_OK);
    }
}
