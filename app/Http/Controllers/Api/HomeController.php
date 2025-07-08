<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CbtTechnique;
use App\Http\Controllers\Api\NestNotesController;
use App\Http\Controllers\Api\SleepTuneController;
use App\Models\NestNotes;
use Illuminate\Support\Carbon;
use App\Models\SleepTune;

class HomeController extends Controller
{

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->User();
        $latest = $user->sleepMetrics()->latest('sleep_date')->first();

        $weekly = $user->sleepMetrics()
            ->where('sleep_date', '>=', now()->subDays(6))
            ->orderBy('sleep_date')
            ->get(['sleep_date', 'sleep_efficiency_pct']);

        $bestDay = $weekly->sortByDesc('sleep_efficiency_pct')->first();
        $caption = $bestDay
            ? "You slept best on " . \Carbon\Carbon::parse($bestDay->sleep_date)->format('l') .
            " with " . $bestDay->sleep_efficiency_pct . "% efficiency."
            : "Sleep trends will appear after your first Nest Time.";

        return response()->json([
            'summary' => $latest ? [
                'efficiency' => $latest->sleep_efficiency_pct,
                'total_sleep' => $latest->total_sleep_time_min,
                'onset_latency' => $latest->sleep_onset_latency_min,
                'date' => $latest->sleep_date
            ] : null,
            'weekly_efficiency' => $weekly->map(fn($w) => [
                'date' => $w->sleep_date,
                'efficiency' => $w->sleep_efficiency_pct
            ]),
            'caption' => $caption,
            'my_path' => $user->myPath,
            'daily_technique' => CbtTechnique::inRandomOrder()->first(),
            'recommended_sounds' => SleepTune::recommended(),
            'recommended_library' => NestNotes::where('is_featured', true)->take(3)->get([
                'id',
                'title',
                'tagline',
                'section'
            ]),
            'recommended_breathing' => CbtTechnique::where('category', 'breathing')
                ->inRandomOrder()
                ->take(1)
                ->get(['id', 'title', 'description']),
        ]);
    }
}
