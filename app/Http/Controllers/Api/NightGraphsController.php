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
            'date' => 'required|date',
            'metrics.Time in Bed (min)' => 'nullable|numeric',
            'metrics.Sleep Onset Latency (min)' => 'nullable|numeric',
            'metrics.Total Sleep Time (TST, min)' => 'nullable|numeric',
            'metrics.Wake After Sleep Onset (WASO, min)' => 'nullable|numeric',
            'metrics.Sleep Efficiency (%)' => 'nullable|numeric',
            'metrics.Number of Awakenings' => 'nullable|numeric',
            'metrics.REM Latency (min)' => 'nullable|string',
            'timeline' => 'required|array'
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        //$patient_id = $user->patient_id;

        $sleepMetric = SleepMetric::updateOrCreate(
            ['sleep_date' => $validated['date'], 'patient_id' => $user->patient_id],
            [
                'time_in_bed_min' => $validated['metrics']['Time in Bed (min)'] ?? null,
                'sleep_onset_latency_min' => $validated['metrics']['Sleep Onset Latency (min)'] ?? null,
                'total_sleep_time_min' => $validated['metrics']['Total Sleep Time (TST, min)'] ?? null,
                'wake_after_sleep_onset_min' => $validated['metrics']['Wake After Sleep Onset (WASO, min)'] ?? null,
                'sleep_efficiency_pct' => $validated['metrics']['Sleep Efficiency (%)'] ?? null,
                'number_of_awakenings' => $validated['metrics']['Number of Awakenings'] ?? null,
                'rem_latency_min' => is_numeric($validated['metrics']['REM Latency (min)'] ?? null) ? $validated['metrics']['REM Latency (min)'] : null,
            ]
        );

        foreach ($request->timeline ?? [] as $segment) {
            $sleepMetric->timelineSegments()->create([
                'state' => $segment['state'],
                'start_time' => $segment['start_time'],
                'end_time' => $segment['end_time'],
                'duration_min' => $segment['duration_min'],
            ]);
        }

        return response()->json([
            'message' => 'AI sleep metrics saved',
            'patient_id' => $user->patient_id,
            'sleep_date' => $validated['date'],
        ], 201);
    }

    // GET: Daily dashboard (pie chart + timeline)
    public function showDaily(Request $request, $date)
    {
        $metric = $request->User()->sleepMetrics()
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
        $weekly = $request->User()->sleepMetrics()
            ->where('sleep_date', '>=', now()->subDays(6)->toDateString())
            ->orderBy('sleep_date')
            ->get(['sleep_date', 'sleep_efficiency_pct']);

        $bestDay = $weekly->sortByDesc('sleep_efficiency_pct')->first();
        $caption = $bestDay
            ? "You slept most efficiently on " . \Carbon\Carbon::parse($bestDay->sleep_date)->format('l') . " with an efficiency of {$bestDay->sleep_efficiency_pct}%."
            : "We're ready when you are. no data yet for the past week.";

        return response()->json([
            'weekly_metrics' => $weekly->map(fn($day) => [
                'date' => $day->sleep_date,
                'efficiency' => $day->sleep_efficiency_pct
            ]),
            'caption' => $caption
        ]);
    }
}
