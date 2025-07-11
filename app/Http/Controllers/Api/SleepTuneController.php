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
            ->map(function ($sound) {
                $sound->url = $sound->file_path
                    ? asset('storage/' . ltrim($sound->file_path, '/'))
                    : null;

                $sound->image_url = $sound->image_path
                    ? asset('storage/' . ltrim($sound->image_path, '/'))
                    : null;

                return $sound;
            });

        return response()->json($sounds);
    }

    //get all sleeptunes
    public function all()
    {
        $sounds = SleepTune::all()->map(function ($sound) {
            return [
                'id' => $sound->id,
                'title' => $sound->title,
                'description' => $sound->description,
                'url' => $sound->file_path
                    ? asset('storage/' . ltrim($sound->file_path, '/'))
                    : null,
                'image_url' => $sound->image_path
                    ? asset('storage/' . ltrim($sound->image_path, '/'))
                    : null,
                'is_featured' => $sound->is_featured,
            ];
        });

        return response()->json($sounds);
    }
}
