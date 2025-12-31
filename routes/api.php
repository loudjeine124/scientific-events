<?php

use Illuminate\Support\Facades\Route;

// API Controllers
use App\Http\Controllers\EventController;

// Other Controllers
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ReviewController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 🟢 Ping Route (test server)
Route::get('/ping', function () {
    return response()->json([
        'message' => 'Server is up and running!',
        'version' => 'Laravel 12',
    ]);
});

// =======================
// 1️⃣ Events
// =======================
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{event}', [EventController::class, 'show']);
Route::post('/events', [EventController::class, 'store']);
Route::put('/events/{event}', [EventController::class, 'update']);
Route::delete('/events/{event}', [EventController::class, 'destroy']);

// =======================
// 2️⃣ Program / Sessions
// =======================
Route::get('/events/{event}/program', [SessionController::class, 'getProgram']);

Route::get('/events/{event}/sessions', [SessionController::class, 'index']);
Route::post('/events/{event}/sessions', [SessionController::class, 'store']);
Route::get('/sessions/{session}', [SessionController::class, 'show']);
Route::put('/sessions/{session}', [SessionController::class, 'update']);
Route::delete('/sessions/{session}', [SessionController::class, 'destroy']);

// =======================
// 3️⃣ Submissions
// =======================
Route::get('/events/{event}/submissions', [SubmissionController::class, 'index']);
Route::post('/events/{event}/submissions', [SubmissionController::class, 'store']);

Route::get('/submissions/{submission}', [SubmissionController::class, 'show']);
Route::put('/submissions/{submission}', [SubmissionController::class, 'update']);
Route::delete('/submissions/{submission}', [SubmissionController::class, 'destroy']);

Route::post(
    '/submissions/{submission}/assign-session/{session}',
    [SubmissionController::class, 'assignToSession']
);

// =======================
// 4️⃣ Reviews
// =======================
Route::get('/submissions/{submission}/reviews', [ReviewController::class, 'index']);
Route::post('/submissions/{submission}/reviews', [ReviewController::class, 'store']);

Route::get('/reviews/{review}', [ReviewController::class, 'show']);
Route::put('/reviews/{review}', [ReviewController::class, 'update']);
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

?>