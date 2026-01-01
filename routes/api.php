<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\SubmissionController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\ReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/




Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{event}', [EventController::class, 'show']);
Route::post('/events', [EventController::class, 'store']);
Route::put('/events/{event}', [EventController::class, 'update']);
Route::delete('/events/{event}', [EventController::class, 'destroy']);


Route::get('/events/{event}/program', [SessionController::class, 'getProgram']);

Route::get('/events/{event}/sessions', [SessionController::class, 'index']);
Route::post('/events/{event}/sessions', [SessionController::class, 'store']);
Route::get('/sessions/{session}', [SessionController::class, 'show']);
Route::put('/sessions/{session}', [SessionController::class, 'update']);
Route::delete('/sessions/{session}', [SessionController::class, 'destroy']);


Route::get('/events/{event}/submissions', [SubmissionController::class, 'index']);
Route::post('/events/{event}/submissions', [SubmissionController::class, 'store']);

Route::get('/submissions/{submission}', [SubmissionController::class, 'show']);
Route::put('/submissions/{submission}', [SubmissionController::class, 'update']);
Route::delete('/submissions/{submission}', [SubmissionController::class, 'destroy']);

Route::post(
    '/submissions/{submission}/assign-session/{session}',
    [SubmissionController::class, 'assignToSession']
);


Route::get('/submissions/{submission}/reviews', [ReviewController::class, 'index']);
Route::post('/submissions/{submission}/reviews', [ReviewController::class, 'store']);

Route::get('/reviews/{review}', [ReviewController::class, 'show']);
Route::put('/reviews/{review}', [ReviewController::class, 'update']);
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
Route::get('/test-encoding', function () {
    return response()->json([
        'test_french' => 'Journée scientifique en Santé 2025',
        'test_arabic' => 'يوم علمي في الصحة 2025',
        'test_encoding' => mb_detect_encoding('Journée')
    ]);
});
?>