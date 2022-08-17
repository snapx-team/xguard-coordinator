<?php

namespace Xguard\Coordinator\Http\Requests;

use Xguard\Coordinator\Models\JobSiteVisit;

class JobSiteVisitPatchRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return  [
            JobSiteVisit::ID => 'required|exists:Xguard\Coordinator\Models\JobSiteVisit,id',
        ];
    }
}
