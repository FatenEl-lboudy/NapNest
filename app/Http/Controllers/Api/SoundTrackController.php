<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SoundTrack;

class SoundTrackController extends Controller
{
    public static function recommended()
{
    $sounds = SoundTrack::where('is_featured', true)
        ->inRandomOrder()
        ->take(3)
        ->get()
        ->transform(fn ($sound) => $sound->setAttribute('url', asset('storage/audio/' . basename($sound->file_path))));

    return response()->json($sounds);
}

}
