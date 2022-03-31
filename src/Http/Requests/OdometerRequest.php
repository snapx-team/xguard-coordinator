<?php

namespace Xguard\Coordinator\Http\Requests;

use Xguard\Coordinator\Models\Odometer;

class OdometerRequest extends BaseFormRequest
{
    const SUPERVISOR_SHIFT_ID_REQUIRED = 'supervisor_shift_id.required';
    const START_ODOMETER_REQUIRED = 'start_odometer.required';
    const END_ODOMETER_REQUIRED = 'end_odometer.required';

    public function rules(): array
    {
        return [
            Odometer::SUPERVISOR_SHIFT_ID => 'required_with:'.Odometer::START_ODOMETER.'|exists:Xguard\Coordinator\Models\SupervisorShift,id',
            Odometer::START_ODOMETER => 'required_without:'.Odometer::END_ODOMETER.'|integer',
            Odometer::END_ODOMETER => 'required_without:'.Odometer::START_ODOMETER.'|integer',
        ];
    }

    public function messages(): array
    {
        return [
            self::SUPERVISOR_SHIFT_ID_REQUIRED => 'Supervisor shift ID required',
            self::START_ODOMETER_REQUIRED => 'Odometer start is required',
            self::END_ODOMETER_REQUIRED => 'Odometer end is required',
        ];
    }
}
