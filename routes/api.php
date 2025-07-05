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
use App\Http\Controllers\Api\SoundTrackController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\CbtTechniqueController;
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
        Route::get('/my-plan', [MyPlanController::class, 'index'])->name('home.my-plan');
        Route::prefix('library')->middleware('auth:sanctum')->group(function () {
            Route::get('/', [LibraryController::class, 'index'])->name('library.index'); 
            Route::get('/sections', [LibraryController::class, 'sections'])->name('library.sections'); 
            Route::get('/section/{slug}', [LibraryController::class, 'bySection'])->name('library.bySection'); // fetch docs in a section
            Route::get('/recommended', [LibraryController::class, 'recommended'])->name('library.recommended');
            Route::get('/{id}', [LibraryController::class, 'show'])->name('library.show'); // fetch single document by ID
        });

        Route::get('/statistics', [StatisticsController::class, 'index'])->name('home.statistics');
        Route::get('/profile', [ProfileController::class, 'show'])->name('home.profile');

        //recommended
        Route::get('/sound-tracks', [SoundTrackController::class, 'recommended'])->name('home.sound-tracks');
        Route::get('/breathing/recommended', [CbtTechniqueController::class, 'recommendedBreathing'])->name('home.breathing.recommended');
    });


    //model inference
   // Route::middleware('auth:sanctum')->post('/', [SleepInferenceController::class, 'store']);
});
