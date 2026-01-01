<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = Session::all();
        return response()->json($sessions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'required|string',
            'session_chair_id' => 'nullable|exists:users,id',
        ]);

        $session = Session::create($validated);
        return response()->json($session, 201);
    }

    public function show($id)
    {
        $session = Session::findOrFail($id);
        return response()->json($session);
    }

    public function update(Request $request, $id)
    {
        $session = Session::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|required|date_format:H:i',
            'end_time' => 'sometimes|required|date_format:H:i|after:start_time',
            'room' => 'sometimes|required|string',
            'session_chair_id' => 'nullable|exists:users,id',
        ]);

        $session->update($validated);
        return response()->json($session);
    }

    public function destroy($id)
    {
        $session = Session::findOrFail($id);
        $session->delete();
        return response()->json(['message' => 'Session deleted successfully']);
    }
}