<?php

namespace Xguard\Coordinator\Models;

use App\Models\User;
use Carbon;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupervisorShift extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    const ID = "id";
    const USER_ID = 'user_id';
    const START_TIME = 'start_time';
    const END_TIME = 'end_time';
    const START_LAT = 'start_lat';
    const START_LNG = 'start_lng';
    const END_LAT = 'end_lat';
    const END_LNG = 'end_lng';
    const DELETED = 'DELETED';
    const USER = 'USER';
    const ODOMETER = 'odometer';

    protected $dates = ['deleted_at', 'start_time', 'end_time'];
    protected $table = 'sa_supervisor_shifts';
    protected $appends = ['is_active'];
    protected $guarded = [];
    protected $cascadeDeletes = ['odometer', 'odometerPhoto', 'stops', 'jobSiteVisits'];
    protected $fillable = [
        self::USER_ID,
        self::START_TIME,
        self::END_TIME
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault(function ($user) {
            $user->first_name = self::DELETED;
            $user->last_name = self::USER;
        });
    }

    public function odometer(): HasOne
    {
        return $this->hasOne(Odometer::class);
    }

    public function locationPings(): HasMany
    {
        return $this->hasMany(LocationPing::class);
    }

    public function jobSiteVisits(): HasMany
    {
        return $this->hasMany(JobSiteVisit::class);
    }

    public function getIsActiveAttribute(): bool
    {
        return (!$this->end_time && $this->start_time &&  $this->start_time >= Carbon::now()->subDay());
    }
}
