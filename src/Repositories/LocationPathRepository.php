<?php

namespace Xguard\Coordinator\Repositories;

use Carbon\Carbon;
use Xguard\Coordinator\Models\LocationPing;

class LocationPathRepository
{
    const FIVE_MINUTES = 5;
    const START_PING_ID = 'startPingId';
    const START_LAT = 'startLat';
    const START_LNG = 'startLng';
    const START_TIME = 'startTime';
    const END_PING_ID = 'endPingId';
    const END_LAT = 'endLat';
    const END_LNG = 'endLng';
    const END_TIME = 'endTime';
    const TOTAL_TIME = 'totalTime';
    const AVERAGE_LAT = 'averageLat';
    const AVERAGE_LNG = 'averageLng';
    const LAT = 'lat';
    const LNG = 'lng';
    const PATH = 'path';
    const STOPS = 'stops';

    public static function getSupervisorShiftPathData(int $supervisorShiftId)
    {
        $locationPings = LocationPing::where(LocationPing::SUPERVISOR_SHIFT_ID, $supervisorShiftId)->get();
        $data = self::formatSupervisorShiftPathData($locationPings);
        return $data;
    }

    private static function formatSupervisorShiftPathData($pings)
    {
        $currentPing = [
            self::START_PING_ID => null,
            self::START_LAT => null,
            self::START_LNG => null,
            self::START_TIME => null,
            self::END_PING_ID => null,
            self::END_LAT => null,
            self::END_LNG => null,
            self::END_TIME => null,
            self::TOTAL_TIME => null,
            self::AVERAGE_LAT => null,
            self::AVERAGE_LNG => null,
        ];

        $stops = [];

        $path = $pings->map(function ($ping) use (&$stops, &$currentPing) {

            $distanceFromPreviousSavedPoint = self::haversineGreatCircleDistance(
                $currentPing[self::START_LAT],
                $currentPing[self::START_LAT],
                $ping->lat,
                $ping->lng
            );

            if ($distanceFromPreviousSavedPoint < 100) {
                $currentPing[self::END_PING_ID] =  $ping->id;
                $currentPing[self::END_LAT] =  $ping->lat;
                $currentPing[self::END_LNG] =  $ping->lng;
                $currentPing[self::END_TIME] =  $ping->created_at;
                $currentPing[self::TOTAL_TIME] =  Carbon::parse($ping->created_at)->diffInMinutes($currentPing[self::START_TIME]);
                $currentPing[self::AVERAGE_LAT] = $currentPing[self::AVERAGE_LAT]? ($ping->lat + $currentPing[self::AVERAGE_LAT])/2 : $ping->lat;
                $currentPing[self::AVERAGE_LNG] =  $currentPing[self::AVERAGE_LNG]? ($ping->lng + $currentPing[self::AVERAGE_LNG])/2 : $ping->lng ;
            } else {
                if ($currentPing[self::TOTAL_TIME] > self::FIVE_MINUTES) {
                    array_push($stops, (object)$currentPing);
                }

                $currentPing = [
                    self::START_LAT => $ping->lat,
                    self::START_LNG => $ping->lng,
                    self::END_LAT => null,
                    self::END_LNG => null,
                    self::START_PING_ID => $ping->id,
                    self::END_PING_ID => null,
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
            ];
        });

        //if last ping includes a stop
        if ($currentPing[self::TOTAL_TIME] >= self::FIVE_MINUTES) {
            array_push($stops, (object)$currentPing);
        }

        return [
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
