<?php

namespace Xguard\Coordinator\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupervisorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fullName' => $this->full_name,
            'email' => $this->email,
            'supervisorShifts'=> SupervisorShiftResource::collection($this->whenLoaded('supervisorShifts'))
        ];
    }
}
