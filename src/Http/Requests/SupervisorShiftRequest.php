<?php

namespace Xguard\Coordinator\Http\Requests;

use Xguard\Coordinator\Models\SupervisorShift;

class SupervisorShiftRequest extends BaseFormRequest
{
    const START_REQUIRED = 'start.required';

    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            SupervisorShift::USER_ID => 'exists:App\Models\User,id',
            SupervisorShift::START_TIME => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            SupervisorShift::USER_ID => 'User id required',
            self::START_REQUIRED => 'A start time is required',
        ];
    }
}
