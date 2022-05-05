<?php

namespace Xguard\Coordinator\Http\Requests;

use Xguard\Coordinator\Models\Odometer;

class OdometerPostRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            Odometer::SUPERVISOR_SHIFT_ID => 'required|exists:Xguard\Coordinator\Models\SupervisorShift,id',
            Odometer::START_ODOMETER => 'required|integer',
        ];
    }
}
