<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CbtTechnique;

class CbtTechniqueController extends Controller
{
    public  static function recommendedBreathing()
    {
        $breathers = CbtTechnique::where('type', 'breathing')
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(2)
            ->get()
            ->map(function ($item) {
                $item->media_url = $item->media_path
                    ? asset('storage/breathing/' . basename($item->media_path))
                    : null;
                return $item;
            });

        return response()->json($breathers);
    }
    //retrieve a single breathing exercise per day for my path
    public function dailyBreathing(Request $request)
    {
        $user = $request->user();
        $offset = optional($user->myPathStartedAt)->diffInDays(now()) ?? 0;

        $technique = CbtTechnique::where('type', 'breathing')
            ->where('is_active', true)
            ->skip($offset)
            ->take(1)
            ->first();

        if (!$technique) {
            return response()->json(['message' => 'No breathing exercise available today.'], 404);
        }

        return response()->json([
            'id' => $technique->id,
            'title' => $technique->title,
            'description' => $technique->description
        ]);
    }

    //get all breathing techniques
    public function allBreathing()
    {
        $items = CbtTechnique::where('type', 'breathing')
            ->where('is_active', true)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'media_url' => $item->media_path
                    ? asset('storage/breathing/' . basename($item->media_path))
                    : null
            ]);

        return response()->json($items);
    }
}
