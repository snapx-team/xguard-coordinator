<?php

namespace Xguard\Coordinator\Http\Requests;

use Xguard\Coordinator\Models\SupervisorShift;

class SupervisorShiftPatchRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            SupervisorShift::ID => 'required|exists:Xguard\Coordinator\Models\SupervisorShift,id',
            SupervisorShift::END_LAT => 'required|numeric',
            SupervisorShift::END_LNG => 'required|numeric',
        ];
    }
}
