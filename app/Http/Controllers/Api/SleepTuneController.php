<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SleepTune;
use Illuminate\Http\Request;

class SleepTuneController extends Controller
{
    public static function recommended()
{
    $sounds = SleepTune::where('is_featured', true)
        ->inRandomOrder()
        ->take(3)
        ->get()
        ->transform(fn ($sound) => $sound->setAttribute('url', asset('storage/audio/' . basename($sound->file_path))));

    return response()->json($sounds);
}

}
