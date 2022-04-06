<?php

namespace Xguard\Coordinator\Actions\Supervisors;

use Lorisleiva\Actions\Action;
use Xguard\Coordinator\Http\Resources\SupervisorResource;
use Xguard\Coordinator\Models\Supervisor;

class GetSupervisorsDataAction extends Action
{
    public function handle(): array
    {
        $supervisors = Supervisor::with('supervisorShifts.jobSiteVisits.jobSite.contracts')->get();
        return ['supervisorsData' => SupervisorResource::collection($supervisors)];
    }
}
