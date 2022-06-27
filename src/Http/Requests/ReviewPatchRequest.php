<?php

namespace Xguard\Coordinator\Http\Requests;

use Xguard\Coordinator\Models\Review;

class ReviewPatchRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            Review::ID => 'required|exists:Xguard\Coordinator\Models\Review,id',
            Review::NOTE => 'nullable|string',
            Review::RATING => 'required|numeric',
        ];
    }
}
