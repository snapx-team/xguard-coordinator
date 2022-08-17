<?php

namespace Xguard\Coordinator\Http\Requests;

use Xguard\Coordinator\Models\JobSiteVisit;

class JobSiteVisitPostRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $addressIdExistsInJobSiteOrSubaddressTable = $this->request->has(JobSiteVisit::IS_PRIMARY_ADDRESS) ?
            [JobSiteVisit::ADDRESS_ID => 'required|exists:App\Models\JobSite,id']
            : [JobSiteVisit::ADDRESS_ID => 'required|exists:App\Models\JobSiteSubaddress,id'];

        $rules = [
            JobSiteVisit::SUPERVISOR_SHIFT_ID => 'required|exists:Xguard\Coordinator\Models\SupervisorShift,id',
            JobSiteVisit::IS_PRIMARY_ADDRESS => 'required',
        ];

        return array_merge($rules, $addressIdExistsInJobSiteOrSubaddressTable);
    }
}
