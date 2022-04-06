<?php

namespace Xguard\Coordinator\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobSiteVisitResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'startTime'=> $this->startTime,
            'endTime'=> $this->endTime,
            'JobSite' => new JobSiteResource($this->whenLoaded('jobSite'))
        ];
    }
}
