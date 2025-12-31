<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function index(Event $event)
    {
        return response()->json($event->sessions()->with('chair')->orderBy('start_time')->get());
    }

    public function store(Request $request, Event $event)
    {
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'room' => 'required|string|max:255',
            'chair_id' => 'nullable|exists:users,id',
        ]);

        $session = $event->sessions()->create($validated);

        return response()->json($session, 201);
    }

    public function show(Session $session)
    {
        return response()->json($session->load(['event', 'chair', 'submissions.author']));
    }

    public function update(Request $request, Session $session)
    {
        

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|required|date|after:start_time',
            'room' => 'sometimes|required|string|max:255',
            'chair_id' => 'nullable|exists:users,id',
        ]);

        $session->update($validated);

        return response()->json($session);
    }

    public function destroy(Session $session)
    {
        
        $session->delete();
        return response()->json(null, 204);
    }

    public function getProgram(Event $event)
    {
        $sessions = $event->sessions()->with(['submissions.author', 'chair'])->orderBy('start_time')->get();
        return response()->json($sessions);
    }
}