<?php

namespace Xguard\Coordinator\Actions\Supervisors;

use Lorisleiva\Actions\Action;
use Xguard\Coordinator\Http\Resources\SupervisorResource;
use Xguard\Coordinator\Models\Supervisor;
use Xguard\Coordinator\Models\SupervisorShift;

class GetSupervisorsDataAction extends Action
{
    const SUPERVISORS_DATA = 'supervisorsData';
    const JOB_SITE_VISITS_JOB_SITE_CONTRACTS = 'jobSiteVisits.jobSite.contracts';

    public function handle(): array
    {
        $supervisors = Supervisor::with([
            Supervisor::SUPERVISOR_SHIFTS => function ($q) {
                $q->whereHas(SupervisorShift::ODOMETER);
                $q->with([self::JOB_SITE_VISITS_JOB_SITE_CONTRACTS, SupervisorShift::ODOMETER]);
            }
        ])->get();
        return [self::SUPERVISORS_DATA => SupervisorResource::collection($supervisors)];
    }
}
