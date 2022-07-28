<?php

namespace Xguard\Coordinator\Http\Requests;

use Xguard\Coordinator\Models\LocationPing;

class LocationPingPostRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            LocationPing::SUPERVISOR_SHIFT_ID => 'required|exists:Xguard\Coordinator\Models\SupervisorShift,id',
            LocationPing::LAT => 'required|numeric',
            LocationPing::LNG => 'required|numeric',
        ];
    }
}
