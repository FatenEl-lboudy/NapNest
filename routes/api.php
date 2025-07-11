<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\PSQIController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\SleepSetupController;
use App\Http\Controllers\Api\SleepTuneController;
use App\Http\Controllers\Api\CbtTechniqueController;
use App\Http\Controllers\Api\MyPathController;
use App\Http\Controllers\Api\NestNotesController;
use App\Http\Controllers\Api\NightGraphsController;
use App\Http\Controllers\Api\NightNotionsController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\AlarmController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are all assigned to the "api" middleware group.
|
*/

// Region User's Authenticaation 
Route::post('/register',    [AuthController::class, 'register']);
Route::post('/login',     [AuthController::class, 'login']);

// Password-reset 
Route::post('password/forget', [PasswordResetController::class, 'requestReset']);
Route::post('password/verify', [PasswordResetController::class, 'verifyCode']);
Route::post('password/reset',  [PasswordResetController::class, 'resetPassword']);

// Logout (must be authenticated)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

//test route 
//Route::get('/me', function (Request $request) {
//return "test route";
//});

// Get current user
Route::get('/user', function (Request $request) {
    return response()->json(['user' => $request->user()]);
})->middleware('auth:sanctum');

//  require a valid sanctum token
Route::middleware('auth:sanctum')->group(function () {

    // Profile
    Route::get('/profile',   [ProfileController::class, 'show']);
    Route::put('/profile',   [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    // PSQI
    Route::get('/psqi/questions',          [PSQIController::class, 'getQuestions']);
    Route::post('/psqi/submit/{patient_id}', [PSQIController::class, 'submitAnswers']);
    Route::get('/psqi/results/{patient_id}', [PSQIController::class, 'getResults']);

    //home
    Route::prefix('home')->middleware('auth:sanctum')->group(function () {
        //actions & nav bar
        Route::get('/', [HomeController::class, 'index'])->name('home.index');
        Route::get('/all', [SleepTuneController::class, 'all']);
        Route::get('/breathing/all', [CbtTechniqueController::class, 'allBreathing']);
        //recommended
        Route::get('/sleep-tunes-recommnded', [SleepTuneController::class, 'recommended'])->name('home.sleep-tunes');
        Route::get('/breathing-recommended', [CbtTechniqueController::class, 'recommendedBreathing'])->name('home.breathing.recommended');
        Route::get('/recommended-nest-notes', [NestNotesController::class, 'recommended'])->name('nest-notes.recommended');
        //nest time home
        Route::prefix('nest-time')->group(function () {
            Route::prefix('device')->group(function () {
                Route::post('/connect', [DeviceController::class, 'connect']);
            });
            Route::prefix('alarm')->group(function () {
                Route::post('/set', [AlarmController::class, 'store']);
                Route::get('/', [AlarmController::class, 'show']);
            });
        });
        //nest notes home
        Route::prefix('nest-notes')->group(function () {
            Route::get('/', [NestNotesController::class, 'index'])->name('nest-notes.index');
            Route::get('/sections', [NestNotesController::class, 'sections'])->name('nest-notes.sections');
            Route::get('/section/{slug}', [NestNotesController::class, 'bySection'])->name('nest-notes.bySection'); // fetch docs in a section
            Route::get('/{id}', [NestNotesController::class, 'show'])->name('nest-notes.show'); // fetch single document by ID
        });
        // home alarm
        Route::prefix('alarm')->group(function () {
            Route::post('/set', [AlarmController::class, 'store']);
            Route::get('/', [AlarmController::class, 'show']);
        });
        //connect my band home
        Route::prefix('device')->group(function () {
            Route::post('/connect', [DeviceController::class, 'connect']);
        });

        //NightGraphs
        Route::middleware('auth:sanctum')->prefix('night-graphs')->group(function () {
            Route::post('/upload', [NightGraphsController::class, 'store']);            // POST ai metrics + timeline
            Route::get('/daily/{date}', [NightGraphsController::class, 'showDaily']);   // GET daily view for flutter pie charts
            Route::get('/weekly', [NightGraphsController::class, 'weeklyEfficiency']);  // GET weekly trend for line chart
        });

        Route::get('/sleep/setup', [SleepSetupController::class, 'index'])->name('home.sleep.setup');
        
        ////check your daily path home
        Route::prefix('my-path')->middleware('auth:sanctum')->group(function () {
            Route::get('/', [MyPathController::class, 'index']);
            Route::get('/night-notions/questions', [NightNotionsController::class, 'questions']);
            Route::get('/night-notions/flashcards/{questionId}', [NightNotionsController::class, 'flashcards']);
            Route::get('/breathing/daily', [CbtTechniqueController::class, 'dailyBreathing']);
            Route::get('/challenge-negative-thoughts', [NestNotesController::class, 'showChallengeThought'])->name('chaalenge-negative-thoughts.show'); 
            Route::post('/complete', [MyPathController::class, 'markComplete']);

            // nest time my path
            Route::prefix('nest-time')->group(function () {
                Route::prefix('device')->group(function () {
                    Route::post('/connect', [DeviceController::class, 'connect']);
                });
                Route::prefix('alarm')->group(function () {
                    Route::post('/set', [AlarmController::class, 'store']);
                    Route::get('/', [AlarmController::class, 'show']);
                });
            });
        });
    });



    Route::get('/profile', [ProfileController::class, 'show'])->name('home.profile');



    //model inference
    // Route::middleware('auth:sanctum')->post('/', [SleepInferenceController::class, 'store']);
});
