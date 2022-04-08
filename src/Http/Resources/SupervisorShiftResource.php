<?php


namespace Xguard\Coordinator\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupervisorShiftResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'startTime'=> $this->start_time,
            'endTime'=> $this->end_time,
            'isActive' => $this->isActive,
            'jobSiteVisits' => JobSiteVisitResource::collection($this->whenLoaded('jobSiteVisits'))
        ];
    }
}
