<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Session; 
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; 

class SubmissionController extends Controller
{
    public function index(Event $event)
    {
        return response()->json($event->submissions()->with('author')->get());
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'required|string|max:255',
            'type' => 'required|in:oral,poster,workshop',
            'file' => 'required|file|mimes:pdf|max:2048', // 2MB max
        ]);

        $path = $request->file('file')->store('submissions', 'public'); // تخزين الملف

        $submission = $event->submissions()->create([
            ...$validated,
            'author_id' => Auth::id(),
            'file_path' => $path,
            'status' => 'pending',
        ]);

        return response()->json($submission, 201);
    }

    public function show(Submission $submission)
    {
        return response()->json($submission->load(['event', 'author', 'sessions', 'reviews']));
    }

    public function update(Request $request, Submission $submission)
    {
        

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'abstract' => 'sometimes|required|string',
            'keywords' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:oral,poster,workshop',
            'file' => 'nullable|file|mimes:pdf|max:2048',
            'status' => 'sometimes|required|in:pending,under_review,accepted,rejected,revise',
        ]);

        if ($request->hasFile('file')) {
           
            if ($submission->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('submissions', 'public');
        }

        $submission->update($validated);

        return response()->json($submission);
    }

    public function destroy(Submission $submission)
    {
        
        if ($submission->file_path) {
            Storage::disk('public')->delete($submission->file_path);
        }
        $submission->delete();
        return response()->json(null, 204);
    }

    public function assignToSession(Request $request, Submission $submission, Session $session)
    {

        $request->validate([
            'order' => 'nullable|integer',
        ]);

        if ($submission->event_id !== $session->event_id) {
            return response()->json(['message' => 'Submission and Session must belong to the same event.'], 400);
        }

        if ($submission->sessions()->where('session_id', $session->id)->exists()) {
            return response()->json(['message' => 'Submission already assigned to this session.'], 409);
        }

        $submission->sessions()->attach($session->id, [
            'order' => $request->order ?? 0,
        ]);

        return response()->json(['message' => 'Submission assigned to session successfully'], 201);
    }
}