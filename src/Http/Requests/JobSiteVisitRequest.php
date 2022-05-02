<?php

namespace Xguard\Coordinator\Http\Requests;

use Xguard\Coordinator\Models\JobSiteVisit;

class JobSiteVisitRequest extends BaseFormRequest
{
    const SUPERVISOR_SHIFT_ID_REQUIRED = 'supervisor_shift_id.required';
    const JOB_SITE_ID_REQUIRED = 'job_site_id.required';
    const START_TIME_REQUIRED = 'start_time.required';
    const END_TIME_REQUIRED = 'end_time.required';

    public function rules(): array
    {
        $addressIdExistsInJobSiteOrSubaddressTable = $this->request->has(JobSiteVisit::IS_PRIMARY_ADDRESS) ?
            [JobSiteVisit::ADDRESS_ID => 'required_without:'.JobSiteVisit::ID.'|exists:App\Models\JobSite,id']
            : [JobSiteVisit::ADDRESS_ID => 'required_without:'.JobSiteVisit::ID.'|exists:App\Models\JobSiteSubaddress,id'];

        $rules = [
            JobSiteVisit::ID => 'required_without:'.JobSiteVisit::SUPERVISOR_SHIFT_ID.'|exists:Xguard\Coordinator\Models\JobSiteVisit,id',
            JobSiteVisit::SUPERVISOR_SHIFT_ID => 'required_without:'.JobSiteVisit::ID.'|exists:Xguard\Coordinator\Models\SupervisorShift,id',
            JobSiteVisit::IS_PRIMARY_ADDRESS => 'required_without:'.JobSiteVisit::ID,
            JobSiteVisit::START_TIME => 'required_without:'.JobSiteVisit::END_TIME.'|date',
            JobSiteVisit::END_TIME => 'required_without:'.JobSiteVisit::START_TIME.'|date',
        ];

        return array_merge($rules, $addressIdExistsInJobSiteOrSubaddressTable);
    }

    public function messages(): array
    {
        return [
            self::SUPERVISOR_SHIFT_ID_REQUIRED => 'Supervisor shift ID required',
            self::START_TIME_REQUIRED => 'Start time is required',
            self::END_TIME_REQUIRED => 'End time is required',
        ];
    }
}
