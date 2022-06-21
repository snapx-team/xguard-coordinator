<?php

namespace Xguard\Coordinator\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OdometerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'supervisorShiftId'=> $this->supervisor_shift_id,
            'startOdometer'=> $this->start_odometer,
            'endOdometer'=> $this->end_odometer,
        ];
    }
}
