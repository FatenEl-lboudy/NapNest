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
}
