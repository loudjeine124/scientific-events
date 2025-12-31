<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // 1. جلب كل الأحداث
    public function index()
    {
        $events = Event::with('organizer')->get(); // جلب الأحداث مع المنظم
        return response()->json($events, 200);
    }

    // 2. إنشاء حدث جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string',
            'theme' => 'required|string',
        ]);

        // ملاحظة: هنا نضع id المنظم يدوياً أو من الـ Auth
        $validated['organizer_id'] = 1; 
        $validated['status'] = 'published';

        $event = Event::create($validated);

        return response()->json([
            'message' => 'Événement créé avec succès',
            'data' => $event
        ], 201);
    }

    // 3. جلب تفاصيل حدث واحد
    public function show(Event $event)
    {
        return response()->json($event->load('organizer'), 200);
    }

    // 4. تحديث حدث
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'status' => 'in:draft,published,completed',
        ]);

        $event->update($validated);

        return response()->json([
            'message' => 'Événement mis à jour',
            'data' => $event
        ], 200);
    }

    // 5. حذف حدث
    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(['message' => 'Événement supprimé'], 200);
    }
}