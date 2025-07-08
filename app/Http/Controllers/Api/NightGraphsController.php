<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SleepMetric;

class NightGraphsController extends Controller
{
    //  POST: Upload metrics + timeline from model inference(handling ai model's output)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sleep_date' => 'required|date',
            'metrics' => 'required|array',
            'timeline' => 'required|array'
        ]);

        $metric = $request->user()->sleepMetrics()->create([
            ...$validated['metrics'],
            'sleep_date' => $validated['sleep_date']
        ]);

        $metric->timelineSegments()->createMany($validated['timeline']);

        return response()->json(['message' => 'Night data stored'], 201);
    }

    // GET: Daily dashboard (pie chart + timeline)
    public function showDaily(Request $request, $date)
    {
        $metric = $request->user()->sleepMetrics()
            ->where('sleep_date', $date)
            ->with('timelineSegments')
            ->first();

        if (!$metric) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return response()->json([
            'summary' => [
                'total_sleep_time' => $metric->total_sleep_time_min,
                'time_in_bed' => $metric->time_in_bed_min,
                'sleep_efficiency' => $metric->sleep_efficiency_pct,
                'wake_after_sleep' => $metric->wake_after_sleep_onset_min,
                'sleep_onset_latency' => $metric->sleep_onset_latency_min,
                'number_of_awakenings' => $metric->number_of_awakenings,
                'rem_latency' => $metric->rem_latency_min,
            ],
            'timeline' => $metric->timelineSegments->map(fn($s) => [
                'state' => $s->state,
                'start_time' => $s->start_time,
                'end_time' => $s->end_time,
                'duration' => $s->duration_min
            ])
        ]);
    }

    //  GET: Weekly trend (line chart)
    public function weeklyEfficiency(Request $request)
    {
        $weekly = $request->user()->sleepMetrics()
            ->where('sleep_date', '>=', now()->subDays(6)->toDateString())
            ->orderBy('sleep_date')
            ->get(['sleep_date', 'sleep_efficiency_pct']);

        $bestDay = $weekly->sortByDesc('sleep_efficiency_pct')->first();
        $caption = $bestDay
            ? "You slept most efficiently on " . \Carbon\Carbon::parse($bestDay->sleep_date)->format('l') . " with an efficiency of {$bestDay->sleep_efficiency_pct}%."
            : "We're ready when you are. no data yet for the past week.";

        return response()->json([
            $weekly->map(fn($day) => [
                'date' => $day->sleep_date,
                'efficiency' => $day->sleep_efficiency_pct
            ]),
            'caption' => $caption
        ]);
    }
}
