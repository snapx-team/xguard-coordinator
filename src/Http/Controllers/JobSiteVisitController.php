<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobSiteSubaddress;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Xguard\Coordinator\Http\Requests\JobSiteVisitPatchRequest;
use Xguard\Coordinator\Http\Requests\JobSiteVisitPostRequest;
use Xguard\Coordinator\Models\JobSiteVisit;

/**
 * Class JobSiteVisitController
 * @package Xguard\Coordinator\Http\Controllers
 * @group Coordinator Plugin
 */

class JobSiteVisitController extends Controller
{
    /**
     * Create Job Site Visit
     * @response 201 {"id": 1}
     * @response 400 {"message": "Error message description"}
     */
    public function store(JobSiteVisitPostRequest $request)
    {
        $jobSiteVisit = JobSiteVisit::create([
            JobSiteVisit::SUPERVISOR_SHIFT_ID => $request->supervisor_shift_id,
            JobSiteVisit::JOB_SITE_ID => $request->is_primary_address?  $request->address_id : JobSiteSubaddress::find($request->address_id)->id,
            JobSiteVisit::START_TIME => $request->start_time
        ]);
        return response([JobSiteVisit::ID => $jobSiteVisit->id], ResponseAlias::HTTP_CREATED);
    }

    /**
     * Update Job Site Visit
     * @response 201 {"id": 1}
     * @response 400 {"message": "Error message description"}
     */
    public function update(JobSiteVisitPatchRequest $request)
    {
        $jobSiteVisit = JobSiteVisit::find($request->id);
        $jobSiteVisit->fill([JobSiteVisit::END_TIME => $request->end_time])->save();
        $jobSiteVisit->refresh();
        return response([JobSiteVisit::ID => $jobSiteVisit->id], ResponseAlias::HTTP_OK);
    }
}
