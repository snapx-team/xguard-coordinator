<?php

namespace Xguard\Coordinator\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OdometerPhoto extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'sa_odometer_photos';
    protected $guarded = [];

    const SUPERVISOR_SHIFT_ID = 'supervisor_shift_id';
    const START_ODOMETER_PHOTO_PATH = 'start_odometer_photo_path';
    const END_ODOMETER_PHOTO_PATH = 'end_odometer_photo_path';

    protected $fillable = [
        self::SUPERVISOR_SHIFT_ID,
        self::START_ODOMETER_PHOTO_PATH,
        self::END_ODOMETER_PHOTO_PATH
    ];

    public function supervisorShift(): BelongsTo
    {
        return $this->belongsTo(SupervisorShift::class);
    }
}
