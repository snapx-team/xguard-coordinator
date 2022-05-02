<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobSiteSubaddress;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Xguard\Coordinator\Http\Requests\JobSiteVisitRequest;
use Xguard\Coordinator\Models\JobSiteVisit;

class JobSiteVisitController extends Controller
{
    public function store(JobSiteVisitRequest $request): \Illuminate\Http\Response
    {
        $jobSiteVisit = JobSiteVisit::create([
            JobSiteVisit::SUPERVISOR_SHIFT_ID => $request->supervisor_shift_id,
            JobSiteVisit::JOB_SITE_ID => $request->is_primary_address?  $request->address_id : JobSiteSubaddress::find($request->address_id)->id,
            JobSiteVisit::START_TIME => $request->start_time
        ]);
        return Response::make([JobSiteVisit::ID => $jobSiteVisit->id], ResponseAlias::HTTP_CREATED);
    }

    public function update(JobSiteVisitRequest $request): \Illuminate\Http\Response
    {
        $jobSiteVisit = JobSiteVisit::find($request->id);
        $jobSiteVisit->fill([JobSiteVisit::END_TIME => $request->end_time])->save();
        $jobSiteVisit->refresh();
        return Response::make([JobSiteVisit::ID => $jobSiteVisit->id], ResponseAlias::HTTP_OK);
    }
}
