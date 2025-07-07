<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SleepAlarm;
use App\Models\Device;

class SleepSetupController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        $alarm = $user->alarm ?? null;

        $device = $user->device ?? null;

        return response()->json([
            'alarm' => $alarm,
            'device' => $device
                ? [
                    'status' => 'connected',
                    'device_name' => $device->name,
                    'battery_level' => $device->battery_level,
                    'last_synced' => $device->last_synced_at,
                ]
                : ['status' => 'not_connected'],
        ]);
    }
}
