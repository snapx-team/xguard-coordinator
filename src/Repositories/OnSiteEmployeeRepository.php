<?php

namespace Xguard\Coordinator\Repositories;

use App\Models\JobSite;
use App\Models\JobSiteSubaddress;
use Carbon\Carbon;

class OnSiteEmployeeRepository
{
    const ID = 'id';
    const IS_PRIMARY_ADDRESS = 'isPrimaryAddress';
    const CONTRACTS_SHIFTS_USER_SHIFT_EMPLOYEE = 'contracts.shifts.userShift.employee';
    const CONTRACTS = 'contracts';
    const SHIFTS = 'shifts';
    const SHIFT_START = 'shift_start';

    public static function getOnSiteEmployees(array $payload)
    {
        $jobSiteId = $payload[self::ID];
        if (!$payload[self::IS_PRIMARY_ADDRESS]) {
            $jobSiteId = JobSiteSubaddress::find($payload[self::ID])->job_site_id;
        }

        $jobSite = JobSite::with(self::CONTRACTS_SHIFTS_USER_SHIFT_EMPLOYEE)
            ->where(JobSite::ID, '=', $jobSiteId)
            ->whereHas(self::CONTRACTS, function ($q) use ($payload) {
                $q->whereHas(self::SHIFTS, function ($q) use ($payload) {
                    $q->whereDate(self::SHIFT_START, '=', Carbon::today());
                });
            })->first();

        return $jobSite ? self::formatEmployeesFromJobSite($jobSite) : [];
    }

    private static function formatEmployeesFromJobSite($jobSite)
    {
        $contract = $jobSite->contracts->first();

        return $contract->shifts->map(function ($shift) use ($contract) {

            $disciplinaryActions = $shift->userShift->employee->disciplinaryActions->map(function ($action) {
                return [
                    'dateOfInfraction' => $action->date_of_infraction,
                    'notes' => $action->notes,
                    'type' => $action->disciplinary_action,
                    'createdBy' => $action->createdBy->full_name
                ];
            });

            return [
                'name' => $shift->userShift->employee->full_name,
                'lateCheckinMinutes' => Carbon::parse($shift->getCheckinAttribute())->isAfter($shift->shift_start) ? Carbon::parse($shift->getCheckinAttribute())->diffInMinutes($shift->shift_start) : 0,
                'earlyCheckoutMinutes' => Carbon::parse($shift->getCheckoutAttribute())->isBefore($shift->shift_end) ? Carbon::parse($shift->getCheckoutAttribute())->diffInMinutes($shift->shift_end) : 0,
                'checkin' => $shift->getCheckinAttribute(),
                'checkout' => $shift->getCheckoutAttribute(),
                'securityLicenseStatus' => $shift->userShift->employee->security_license_status,
                'securityLicenseNumber' => $shift->userShift->employee->security_license_number,
                'shiftCategory' => $shift->userShift->getShiftCategory(),
                'contactInfo' => [
                    'email' => $shift->userShift->employee->email,
                    'phoneLines' => [
                        'tel1' => [
                            'number' => $shift->userShift->employee->tel_1,
                            'textEnabled' => $shift->userShift->employee->tel_1_can_receive_texts,
                        ],
                        'tel2' => [
                            'number' => $shift->userShift->employee->tel_2,
                            'textEnabled' => $shift->userShift->employee->tel_2_can_receive_texts,
                        ],
                    ],
                ],
                'disciplinaryActions' => $disciplinaryActions->toArray(),
                'contractName' => $contract->contract_identifier,
                'shiftStart' => $shift->shift_start,
                'shiftEnd' => $shift->shift_end,
                'shiftAddress' => [
                    'address' => $shift->shift_address['formatted_address'],
                    'lng' => $shift->shift_address['longitude'],
                    'lat' => $shift->shift_address['latitude'],
                ]
            ];
        });
    }
}
