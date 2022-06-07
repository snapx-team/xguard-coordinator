<?php

namespace Xguard\Coordinator\Repositories;

use App\Models\JobSite;
use App\Models\JobSiteSubaddress;
use Carbon\Carbon;
use Xguard\Coordinator\Models\JobSiteVisit;
use Xguard\Coordinator\Models\Review;

class OnSiteEmployeeRepository
{
    const ID = 'id';
    const CONTRACTS = 'contracts';
    const SHIFTS = 'shifts';
    const USER_SHIFT = 'userShift';
    const EMPLOYEE = 'employee';
    const SHIFT_START = 'shift_start';

    public static function getOnSiteEmployees(int $id, bool $isPrimaryAddress)
    {
        $jobSiteId = $id;
        if (!$isPrimaryAddress) {
            $jobSiteId = JobSiteSubaddress::find($id)->job_site_id;
        }
        $jobSite = JobSite::where(JobSite::ID, '=', $jobSiteId)
            ->whereHas(self::CONTRACTS)->with([
                'contracts' => function ($q) {
                    $q->whereHas(self::SHIFTS);
                    $q->with([
                        'shifts' => function ($q) {
                            $q->whereHas(self::USER_SHIFT);
                            $q->whereDate(self::SHIFT_START, Carbon::today());
                            $q->with([
                                'userShift' => function ($q) {
                                    $q->whereHas(self::EMPLOYEE);
                                    $q->with([
                                        'employee' => function ($q) {
                                            $q->with(['userEvaluation']);
                                        }
                                    ]);
                                }
                            ]);
                        }
                    ]);
                }
            ])->first();
        return $jobSite ? self::formatEmployeesFromJobSite($jobSite) : [];
    }

    private static function formatEmployeesFromJobSite($jobSite)
    {
        $contract = $jobSite->contracts->first();
        return $contract->shifts->map(function ($shift) use ($contract) {
            $disciplinaryActions = $shift->userShift->employee->disciplinaryActions->map(function ($action) {
                return [
                    'id' => $action->id,
                    'dateOfInfraction' => $action->date_of_infraction,
                    'notes' => $action->notes,
                    'type' => $action->disciplinary_action,
                    'createdBy' => $action->createdBy->getFullNameAttribute(),
                ];
            });

            $review = Review::where(Review::USER_SHIFT_ID, $shift->userShift->id)->first();

            return [
                'id' => $shift->userShift->employee->id,
                'name' => $shift->userShift->employee->getFullNameAttribute(),
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
                'review' => $review ? [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'note' => $review->note,
                ] : null,
                'evaluation' => [
                    'id' => $shift->userShift->employee->userEvaluation->id,
                    'workQuality' => $shift->userShift->employee->userEvaluation->work_quality,
                    'punctuality' => $shift->userShift->employee->userEvaluation->punctuality,
                    'teamwork' => $shift->userShift->employee->userEvaluation->team_work,
                    'followsDirection' => $shift->userShift->employee->userEvaluation->follows_directions,
                    'communication' => $shift->userShift->employee->userEvaluation->communication,
                    'average' => $shift->userShift->employee->userEvaluation->average,
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
