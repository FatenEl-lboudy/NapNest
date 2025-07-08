<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CbtTechnique;
use App\Models\NestNotes;
use App\Models\Alarm;
use App\Models\MyPath;

class MyPathController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->User();

        return response()->json([
            'day' => $user->myPath?->day_index ?? 1,
            'reading' => NestNotes::inRandomOrder()->first(['title', 'tagline', 'section']),
            'reset' => CbtTechnique::where('type', 'breathing')->inRandomOrder()->first(['title', 'description']),
            'challenge_prompt' => 'Ready to reframe a Night Notion?'
        ]);
    }

    public function markComplete()
    {
        /** @var \App\Models\User $user */
        $user = auth()->User();
        $path = $user->myPath;

        if (!$path) {
            $path = $user->myPath()->create([
                'title' => 'Your personal path',
                'instructions' => 'Start your guided journey.',
                'day_index' => 1,
                'scheduled_for' => now()
            ]);
        } else {
            $path->increment('day_index');
            $path->scheduled_for = now()->addDay();
            $path->save();
        }
        return response()->json(['message' => 'My Path progress updated.']);
        
    }
    public function startNestTime(Request $request)
{
    $user = $request->user();

    $path = $user->myPath ?? $user->myPath()->create([
        'title' => 'Your nightly path',
        'instructions' => 'Follow this flow every evening.',
        'day_index' => 1,
        'scheduled_for' => now()
    ]);

    $path->scheduled_for = now()->addDay();
    $path->save();

    // Optional: set alarm alongside nest time if user device is linked
    if ($request->has('alarm_time')) {
    $user->alarms()->updateOrCreate(
        ['patient_id' => $user->patient_id],
        [
            'wake_time' => $request->alarm_time,
            'vibration' => true,
            'is_enabled' => true,
        ]
    );
}


    return response()->json(['message' => 'Nest Time started.']);
}

}
