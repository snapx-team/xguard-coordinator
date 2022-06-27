<?php

namespace Xguard\Coordinator\Http\Requests;

use Xguard\Coordinator\Models\Review;

class ReviewPostRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            Review::USER_SHIFT_ID => 'required|exists:App\Models\UserShift,id',
            Review::NOTE => 'nullable|string',
            Review::RATING => 'required|numeric',
        ];
    }
}
