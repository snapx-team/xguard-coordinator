<?php

namespace Xguard\Coordinator\Http\Requests;

use Xguard\Coordinator\Models\SupervisorShift;

class SupervisorShiftPostRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            SupervisorShift::USER_ID => 'required|exists:App\Models\User,id',
            SupervisorShift::START_TIME => 'required|date',
            SupervisorShift::START_LAT => 'required|numeric',
            SupervisorShift::START_LNG => 'required|numeric',

        ];
    }
}
