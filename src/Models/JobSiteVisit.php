<?php

namespace Xguard\Coordinator\Models;

use App\Models\JobSite;
use App\Models\JobSiteSubaddress;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobSiteVisit extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    const ID = 'id';
    const JOB_SITE_ID = 'job_site_id';
    const ADDRESS_ID = 'address_id';
    const IS_PRIMARY_ADDRESS = 'is_primary_address';
    const SUPERVISOR_SHIFT_ID = 'supervisor_shift_id';
    const START_TIME = 'start_time';
    const END_TIME = 'end_time';

    protected $dates = ['deleted_at'];
    protected $table = 'sa_job_site_visits';
    protected $fillable = [
        self::SUPERVISOR_SHIFT_ID,
        self::JOB_SITE_ID,
        self::START_TIME,
        self::END_TIME
    ];
    protected $appends = [
        'address',
    ];

    public function jobSite(): BelongsTo
    {
        return $this->belongsTo(JobSite::class);
    }

    public function supervisorShift(): BelongsTo
    {
        return $this->belongsTo(SupervisorShift::class);
    }

    public function getAddressAttribute(): array
    {
        if (!$this->job_site_subaddress_id) {
            $jobSite = JobSite::where('id', $this->job_site_id)->first();
            $address = [
                'id' => $jobSite->id,
                'name' => $jobSite->google_formatted_address,
                'lat' => $jobSite->google_coordinates_lat,
                'lng' => $jobSite->google_coordinates_lng,
            ];
        } else {
            $jobSiteSubAddress = JobSiteSubaddress::where('id', $this->job_site_subaddress_id)->first();
            $address = [
                'id' => $jobSiteSubAddress->id,
                'name' => $jobSiteSubAddress->formatted_address,
                'lat' => $jobSiteSubAddress->latitude,
                'lng' => $jobSiteSubAddress->longitude,
            ];
        }
        return $address;
    }
}
