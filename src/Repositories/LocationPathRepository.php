<?php

namespace Xguard\Coordinator\Repositories;

use Carbon\Carbon;
use Xguard\Coordinator\Models\LocationPing;

class LocationPathRepository
{
    const START_LAT = 'startLat';
    const START_LNG = 'startLng';
    const START_TIME = 'startTime';
    const END_LAT = 'endLat';
    const END_LNG = 'endLng';
    const END_TIME = 'endTime';
    const AVERAGE_LAT = 'averageLat';
    const AVERAGE_LNG = 'averageLng';
    const TOTAL_TIME = 'totalTime';
    const PATH = 'path';
    const STOPS = 'stops';
    const LAT = 'lat';
    const LNG = 'lng';
    const TOTAL_DISTANCE = 'totalDistance';
    const CREATED_AT = 'createdAt';

    public static function getSupervisorShiftPathData(
        int $supervisorShiftId,
        int $timeThreshold,
        int $distanceThreshold
    ) {
        $locationPings = LocationPing::where(LocationPing::SUPERVISOR_SHIFT_ID, $supervisorShiftId)->get();
        if ($locationPings->isEmpty()) {
            return null;
        }
        $data = self::formatSupervisorShiftPathData($locationPings, $timeThreshold, $distanceThreshold);
        return $data;
    }

    private static function formatSupervisorShiftPathData($pings, int $timeThreshold, int $distanceThreshold)
    {
        $firstPing = $pings->shift();
        $currentPing = [
            self::START_LAT => $firstPing->lat,
            self::START_LNG => $firstPing->lng,
            self::START_TIME => $firstPing->created_at,
            self::END_TIME => null,
            self::END_LAT => null,
            self::END_LNG => null,
            self::TOTAL_TIME => null,
            self::AVERAGE_LAT => null,
            self::AVERAGE_LNG => null,
        ];

        $stops = [];

        $totalDistance = 0;

        $path = $pings->map(function ($ping) use (
            $distanceThreshold,
            $timeThreshold,
            &$stops,
            &$currentPing,
            &$totalDistance
        ) {

            $distanceFromPreviousSavedPoint = self::haversineGreatCircleDistance(
                $currentPing[self::START_LAT],
                $currentPing[self::START_LNG],
                $ping->lat,
                $ping->lng
            );

            if ($distanceFromPreviousSavedPoint < $distanceThreshold) {
                $currentPing[self::END_LAT] = $ping->lat;
                $currentPing[self::END_LNG] = $ping->lng;
                $currentPing[self::END_TIME] = $ping->created_at;
                $currentPing[self::TOTAL_TIME] = Carbon::parse($ping->created_at)->diffInMinutes($currentPing[self::START_TIME]);
                $currentPing[self::AVERAGE_LAT] = $currentPing[self::AVERAGE_LAT] ? ($ping->lat + $currentPing[self::AVERAGE_LAT]) / 2 : $ping->lat;
                $currentPing[self::AVERAGE_LNG] = $currentPing[self::AVERAGE_LNG] ? ($ping->lng + $currentPing[self::AVERAGE_LNG]) / 2 : $ping->lng;
            } else {
                if ($currentPing[self::TOTAL_TIME] >= $timeThreshold) {
                    array_push($stops, (object) $currentPing);
                }
                $totalDistance += $distanceFromPreviousSavedPoint;

                $currentPing = [
                    self::START_LAT => $ping->lat,
                    self::START_LNG => $ping->lng,
                    self::END_LAT => null,
                    self::END_LNG => null,
                    self::START_TIME => $ping->created_at,
                    self::END_TIME => null,
                    self::TOTAL_TIME => null,
                    self::AVERAGE_LAT => null,
                    self::AVERAGE_LNG => null,
                ];
            }

            return [
                self::LAT => $ping->lat,
                self::LNG => $ping->lng,
                self::CREATED_AT => $ping->created_at,
            ];
        });

        //if last ping includes a stop
        if ($currentPing[self::TOTAL_TIME] >= $timeThreshold) {
            array_push($stops, (object) $currentPing);
        }

        return [
            self::TOTAL_DISTANCE => $totalDistance,
            self::PATH => $path,
            self::STOPS => $stops,
        ];
    }

    private static function haversineGreatCircleDistance(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo,
        $earthRadius = 6371000
    ) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}
