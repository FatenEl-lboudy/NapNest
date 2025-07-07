<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\PSQIController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\SleepSetupController;
use App\Http\Controllers\Api\MyPlanController;
use App\Http\Controllers\Api\LibraryController;
use App\Http\Controllers\Api\SleepTuneController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\CbtTechniqueController;
use App\Http\Controllers\Api\MyPathController;
use App\Http\Controllers\Api\NestNotesController;
use App\Http\Controllers\Api\SleepInferenceController;

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
Route::post('/login',       [AuthController::class, 'login']);

// Password-reset 
Route::post('password/forget', [PasswordResetController::class, 'requestReset']);
Route::post('password/verify', [PasswordResetController::class, 'verifyCode']);
Route::post('password/reset',  [PasswordResetController::class, 'resetPassword']);

// Logout (must be authenticated)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

//test route 
//Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    //return $request->user();
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
        Route::get('/sleep/setup', [SleepSetupController::class, 'index'])->name('home.sleep.setup');
        Route::get('/my-path', [MyPathController::class, 'index'])->name('home.my-path');
        Route::prefix('nest-notes')->group(function () {
            Route::get('/', [NestNotesController::class, 'index'])->name('nest-notes.index'); 
            Route::get('/sections', [NestNotesController::class, 'sections'])->name('nest-notes.sections'); 
            Route::get('/section/{slug}', [NestNotesController::class, 'bySection'])->name('nest-notes.bySection'); // fetch docs in a section
            Route::get('/recommended', [NestNotesController::class, 'recommended'])->name('nest-notes.recommended');
            Route::get('/{id}', [NestNotesController::class, 'show'])->name('nest-notes.show'); // fetch single document by ID
        });

        Route::get('/statistics', [StatisticsController::class, 'index'])->name('home.statistics');
        Route::get('/profile', [ProfileController::class, 'show'])->name('home.profile');

        //recommended
        Route::get('/sleep-tuness', [SleepTuneController::class, 'recommended'])->name('home.sleep-tunes');
        Route::get('/breathing/recommended', [CbtTechniqueController::class, 'recommendedBreathing'])->name('home.breathing.recommended');
    });


    //model inference
   // Route::middleware('auth:sanctum')->post('/', [SleepInferenceController::class, 'store']);
});
