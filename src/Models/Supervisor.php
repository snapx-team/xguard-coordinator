<?php

namespace Xguard\Coordinator\Models;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supervisor extends User
{
    const SUPERVISOR_SHIFTS = 'supervisorShifts';
    
    protected $table = 'users';
    protected $appends = [
        self::FULL_NAME,
    ];
    protected $with = [];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->whereHas("user", function ($q) {
                $q->role(Roles::SUPERVISOR()->getValue());
            });
        });
    }

    public function supervisorShifts(): HasMany
    {
        return $this->HasMany(SupervisorShift::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}
