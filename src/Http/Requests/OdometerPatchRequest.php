<?php

namespace Xguard\Coordinator\Http\Requests;

use Xguard\Coordinator\Models\Odometer;

class OdometerPatchRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            Odometer::ID => 'required|exists:Xguard\Coordinator\Models\Odometer,id',
            Odometer::END_ODOMETER => 'required|integer',
        ];
    }
}
