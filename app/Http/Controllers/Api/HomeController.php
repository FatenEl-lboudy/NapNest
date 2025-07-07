<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SoundTune;
use App\Models\CbtTechnique;
use App\Http\Controllers\Api\NestNotesController;
use App\Http\Controllers\Api\SoundTuneController;
use App\Models\SleepTune;

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
            'recommended_sounds' => SleepTune::recommended(),
            'recommended_library' => NestNotesController::recommended(),
            'recommended_breathing' => CbtTechniqueController::recommendedBreathing(),
        ]);
    }
}
