<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alarm;
use Illuminate\Http\Request;

class AlarmController extends Controller
{
    // POST /alarm/set
    public function store(Request $request)
    {
        $validated = $request->validate([
            'wake_time' => 'required|date_format:H:i',
            'vibration' => 'sometimes|boolean',
            'sound_label' => 'nullable|string',
            'is_enabled' => 'sometimes|boolean'
        ]);

        $user = $request->user();

        $alarm = Alarm::updateOrCreate(
            ['patient_id' => $user->patient_id],
            [
                'wake_time' => $validated['wake_time'],
                'vibration' => $validated['vibration'] ?? true,
                'sound_label' => $validated['sound_label'] ?? null,
                'is_enabled' => $validated['is_enabled'] ?? true,
            ]
        );

        return response()->json([
            'message' => 'Alarm saved successfully.',
            'alarm' => $alarm
        ]);
    }

    // GET /alarm
    public function show(Request $request)
    {
        $alarm = Alarm::where('patient_id', $request->user()->patient_id)->first();

        return response()->json([
            'alarm' => $alarm ?? null
        ]);
    }
}
