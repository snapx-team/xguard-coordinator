<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Xguard\Coordinator\Http\Requests\LocationPingPostRequest;
use Xguard\Coordinator\Models\LocationPing;
use Xguard\Coordinator\Repositories\LocationPathRepository;

class LocationPingController extends Controller
{
    const TIME_THRESHOLD = 'timeThreshold';
    const DISTANCE_THRESHOLD = 'distanceThreshold';

    public function store(LocationPingPostRequest $request): \Illuminate\Http\Response
    {
        $locationPing = LocationPing::create([
            LocationPing::SUPERVISOR_SHIFT_ID => $request->supervisor_shift_id,
            LocationPing::LAT => $request->lat,
            LocationPing::LNG => $request->lng
        ]);
        return Response::make([LocationPing::ID => $locationPing->id], ResponseAlias::HTTP_CREATED);
    }

    public function getSupervisorShiftLocationPings(int $supervisorShiftId, Request $request)
    {
        $thresholds = $request->all();
        return LocationPathRepository::getSupervisorShiftPathData($supervisorShiftId, $thresholds[self::TIME_THRESHOLD], $thresholds[self::DISTANCE_THRESHOLD]);
    }
}
