<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    // POST /device/connect
    public function connect(Request $request)
    {
        $validated = $request->validate([
            'device_name' => 'required|string',
            'serial_number' => 'nullable|string',
            'connection_type' => 'in:bluetooth,USB,BLE',
            'battery_level' => 'nullable|integer',
        ]);

        $device = Device::updateOrCreate(
            [
                'patient_id' => $request->user()->patient_id,
                'serial_number' => $validated['serial_number'],
            ],
            [
                'device_name' => $validated['device_name'],
                'connection_type' => $validated['connection_type'] ?? 'bluetooth',
                'battery_level' => $validated['battery_level'],
                'last_synced_at' => now(),
                'status' => 'connected',
            ]
        );

        return response()->json([
            'message' => 'Device connected successfully.',
            'device' => $device
        ]);
    }
}
