<?php

namespace Xguard\Coordinator\Models;

use App\Models\JobSite;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobSiteVisit extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'sa_job_site_visits';
    protected $guarded = [];

    public function jobSite(): BelongsTo
    {
        return $this->belongsTo(JobSite::class);
    }

    public function supervisorShift(): BelongsTo
    {
        return $this->belongsTo(SupervisorShift::class);
    }
}
