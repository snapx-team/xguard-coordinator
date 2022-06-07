<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Xguard\Coordinator\Http\Requests\ReviewPatchRequest;
use Xguard\Coordinator\Http\Requests\ReviewPostRequest;
use Xguard\Coordinator\Models\Review;

/**
 * Class ReviewController
 * @package Xguard\Coordinator\Http\Controllers
 * @group Coordinator Plugin
 */
class ReviewController extends Controller
{
    /**
     * Create Review
     * @response 201 {"id": 1}
     * @response 400 {"message": "Error message description"}
     */
    public function store(ReviewPostRequest $request)
    {
        $review = Review::create([
            Review::USER_SHIFT_ID => $request->user_shift_id,
            Review::NOTE => $request->note,
            Review::RATING => $request->rating,

        ]);
        return response([Review::ID => $review->id], ResponseAlias::HTTP_CREATED);
    }

    /**
     * Update Review
     * @response 201 {"id": 1}
     * @response 400 {"message": "Error message description"}
     */
    public function update(ReviewPatchRequest $request)
    {
        $review = Review::find($request->id);
        $review->fill([
            Review::NOTE => $request->note,
            Review::RATING => $request->rating,
        ])->save();
        $review->refresh();
        return response([Review::ID => $review->id], ResponseAlias::HTTP_OK);
    }

    /**
     * Delete Review
     * @response 201 {"success": "true"}
     * @response 400 {"message": "Error message description"}
     */
    public function delete($id)
    {
        try {
            $review = Review::find($id);
            $review->delete();
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
        return response(['success' => 'true'], ResponseAlias::HTTP_OK);
    }
}
