<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\PSQIController;

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

// Get current user
Route::get('/user', function (Request $request) {
    return response()->json(['user' => $request->user()]);
})->middleware('auth:sanctum');

//  require a valid sanctum token
Route::middleware('auth:sanctum')->group(function () {

    // Profile
    Route::get('/profile',   [ProfileController::class, 'show']);
    Route::put('/profile',   [ProfileController::class, 'update']);
    Route::delete('/profile',[ProfileController::class, 'destroy']);

    // PSQI
    Route::get('/psqi/questions',          [PSQIController::class, 'getQuestions']);
    Route::post('/psqi/submit/{patient_id}', [PSQIController::class, 'submitAnswers']);
    Route::get('/psqi/results/{patient_id}', [PSQIController::class, 'getResults']);

});
