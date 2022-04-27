<?php

namespace Xguard\Coordinator\Http\Requests;

use Xguard\Coordinator\Models\SupervisorShift;

class SupervisorShiftRequest extends BaseFormRequest
{
    const USER_ID_REQUIRED= 'user_id.required';
    const START_REQUIRED = 'start_time.required';
    const END_REQUIRED = 'end_time.required';

    public function rules(): array
    {
        return [
            SupervisorShift::ID => 'required_without:'.SupervisorShift::USER_ID. '|exists:Xguard\Coordinator\Models\SupervisorShift,id',
            SupervisorShift::USER_ID => 'required_without:'.SupervisorShift::ID.'|exists:App\Models\User,id',
            SupervisorShift::START_TIME => 'required_without:'.SupervisorShift::END_TIME.'|date',
            SupervisorShift::START_LAT => 'required_with:'.SupervisorShift::START_TIME.'|numeric',
            SupervisorShift::START_LNG => 'required_with:'.SupervisorShift::START_TIME.'|numeric',
            SupervisorShift::END_TIME => 'required_without:'.SupervisorShift::START_TIME.'|date',
            SupervisorShift::END_LAT => 'required_with:'.SupervisorShift::END_TIME.'|numeric',
            SupervisorShift::END_LNG => 'required_with:'.SupervisorShift::END_TIME.'|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            self::USER_ID_REQUIRED => 'User ID required',
            self::START_REQUIRED => 'An odometer start time is required',
            self::END_REQUIRED => 'An odometer end time is required',
        ];
    }
}
