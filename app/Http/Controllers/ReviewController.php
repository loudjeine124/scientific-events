<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Submission; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(Submission $submission)
    {
          return response()->json($submission->reviews()->with('reviewer')->get());
    }

    public function store(Request $request, Submission $submission)
    {
       
        $validated = $request->validate([
            'scientific_quality' => 'required|integer|min:1|max:5',
            'relevance' => 'required|integer|min:1|max:5',
            'originality' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
            'recommendation' => 'required|in:accept,reject,revise',
        ]);

        
        if ($submission->reviews()->where('reviewer_id', Auth::id())->exists()) {
            return response()->json(['message' => 'You have already reviewed this submission.'], 409);
        }

        $review = $submission->reviews()->create([
            ...$validated,
            'reviewer_id' => Auth::id(),
        ]);

      
        $this->updateSubmissionStatus($submission);

        return response()->json($review, 201);
    }

    public function show(Review $review)
    {
        return response()->json($review->load('reviewer', 'submission'));
    }

    public function update(Request $request, Review $review)
    {
       
        if (Auth::id() !== $review->reviewer_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'scientific_quality' => 'sometimes|required|integer|min:1|max:5',
            'relevance' => 'sometimes|required|integer|min:1|max:5',
            'originality' => 'sometimes|required|integer|min:1|max:5',
            'comments' => 'nullable|string',
            'recommendation' => 'sometimes|required|in:accept,reject,revise',
        ]);

        $review->update($validated);

        
        $this->updateSubmissionStatus($review->submission);

        return response()->json($review);
    }

    public function destroy(Review $review)
    {
       
        if (Auth::id() !== $review->reviewer_id && Auth::id() !== $review->submission->event->organizer_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $submission = $review->submission; 
        $review->delete();
        $this->updateSubmissionStatus($submission); 
        return response()->json(null, 204);
    }

    protected function updateSubmissionStatus(Submission $submission)
    {
        $reviews = $submission->reviews;
        $accepts = $reviews->where('recommendation', 'accept')->count();
        $rejects = $reviews->where('recommendation', 'reject')->count();
        $revises = $reviews->where('recommendation', 'revise')->count();

       
        if ($reviews->count() >= 3) {
            if ($accepts >= 2) {
                $submission->update(['status' => 'accepted']);
            } elseif ($rejects >= 2) {
                $submission->update(['status' => 'rejected']);
            } elseif ($revises >= 2) {
                $submission->update(['status' => 'revise']);
            } else {
               
                $submission->update(['status' => 'under_review']);
            }
        } elseif ($reviews->count() > 0) {
            $submission->update(['status' => 'under_review']);
        } else {
            $submission->update(['status' => 'pending']);
        }
    }
}