<?php

namespace Xguard\Coordinator\Models;

use App\Models\UserShift;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    const ID = 'id';
    const USER_SHIFT_ID = 'user_shift_id';
    const RATING = 'rating';
    const NOTE = 'note';
    const DELETED_AT = 'deleted_at';

    protected $dates = ['deleted_at'];
    protected $table = 'sa_reviews';
    protected $guarded = [];

    public function userShift(): BelongsTo
    {
        return $this->belongsTo(UserShift::class);
    }
}
