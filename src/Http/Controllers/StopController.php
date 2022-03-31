<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Xguard\Coordinator\Http\Requests\StopRequest;
use Xguard\Coordinator\Models\Stop;

class StopController extends Controller
{
    public function store(StopRequest $request): \Illuminate\Http\Response
    {
        $stop = Stop::create([
            Stop::SUPERVISOR_SHIFT_ID => $request->supervisor_shift_id,
            Stop::START_TIME => $request->start_time,
            Stop::END_TIME => $request->end_time
        ]);
        return Response::make([Stop::ID => $stop->id], ResponseAlias::HTTP_CREATED);
    }
}
