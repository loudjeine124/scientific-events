<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index()
    {
        $submissions = Submission::all();
        return response()->json($submissions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'required|string',
            'type' => 'required|in:oral,poster,workshop',
            'file_path' => 'nullable|string',
            'status' => 'sometimes|in:pending,accepted,rejected,revision',
        ]);

        $submission = Submission::create($validated);
        return response()->json($submission, 201);
    }

    public function show($id)
    {
        $submission = Submission::findOrFail($id);
        return response()->json($submission);
    }

    public function update(Request $request, $id)
    {
        $submission = Submission::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'abstract' => 'sometimes|required|string',
            'keywords' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:oral,poster,workshop',
            'file_path' => 'nullable|string',
            'status' => 'sometimes|in:pending,accepted,rejected,revision',
        ]);

        $submission->update($validated);
        return response()->json($submission);
    }

    public function destroy($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->delete();
        return response()->json(['message' => 'Submission deleted successfully']);
    }
}