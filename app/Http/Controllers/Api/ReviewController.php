<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::all();
        return response()->json($reviews);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'submission_id' => 'required|exists:submissions,id',
            'reviewer_id' => 'required|exists:users,id',
            'relevance_score' => 'required|integer|min:1|max:5',
            'quality_score' => 'required|integer|min:1|max:5',
            'originality_score' => 'required|integer|min:1|max:5',
            'comments' => 'required|string',
            'recommendation' => 'required|in:accept,reject,revise',
        ]);

        $review = Review::create($validated);
        return response()->json($review, 201);
    }

    public function show($id)
    {
        $review = Review::findOrFail($id);
        return response()->json($review);
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $validated = $request->validate([
            'relevance_score' => 'sometimes|required|integer|min:1|max:5',
            'quality_score' => 'sometimes|required|integer|min:1|max:5',
            'originality_score' => 'sometimes|required|integer|min:1|max:5',
            'comments' => 'sometimes|required|string',
            'recommendation' => 'sometimes|required|in:accept,reject,revise',
        ]);

        $review->update($validated);
        return response()->json($review);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        return response()->json(['message' => 'Review deleted successfully']);
    }
}