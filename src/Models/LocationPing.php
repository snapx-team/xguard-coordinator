<?php

namespace Xguard\Coordinator\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationPing extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    const ID = "id";
    const SUPERVISOR_SHIFT_ID = 'supervisor_shift_id';
    const LAT = 'lat';
    const LNG = 'lng';

    protected $dates = ['deleted_at'];
    protected $table = 'sa_location_pings';
    protected $guarded = [];

    public function supervisorShift(): BelongsTo
    {
        return $this->belongsTo(SupervisorShift::class);
    }
}
