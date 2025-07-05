<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SoundTrack;
use App\Models\CbtTechnique;
use App\Http\Controllers\Api\LibraryController;
use App\Http\Controllers\Api\SoundTrackController;



class HomeController extends Controller
{
    
    public function index()
    {
        $user = auth()->user();


        $technique = CbtTechnique::inRandomOrder()->first();

        return response()->json([
            'sleep_record' => $user->latestSleepRecord,
            'my_plan' => $user->myPlan,
            'daily_technique' => $technique,
            'recommended_sounds' => SoundTrackController::recommended(),
            'recommended_library' => LibraryController::recommended(),
            'recommended_breathing' => CbtTechniqueController::recommendedBreathing(),
        ]);
    }
}
