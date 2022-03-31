<?php

namespace Xguard\Coordinator\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Odometer extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    const ID = "id";
    const SUPERVISOR_SHIFT_ID = 'supervisor_shift_id';
    const START_ODOMETER = 'start_odometer';
    const END_ODOMETER = 'end_odometer';

    protected $dates = ['deleted_at'];
    protected $table = 'sa_odometers';
    protected $fillable = [
        self::SUPERVISOR_SHIFT_ID,
        self::START_ODOMETER,
        self::END_ODOMETER
    ];

    public function supervisorShift(): BelongsTo
    {
        return $this->belongsTo(SupervisorShift::class);
    }
}
