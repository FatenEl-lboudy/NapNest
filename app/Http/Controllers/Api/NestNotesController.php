<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\NestNotes;

class NestNotesController extends Controller
{
    public function index()
    {
        // Get all items grouped by section
        $grouped = NestNotes::all()
            ->groupBy('section')
            ->map(function ($items, $section) {
                return [
                    'section' => $section,
                    'slug' => Str::slug($section),
                    'tagline' => optional($items->first())->tagline,
                    'documents' => $items->map(fn($item) => [
                        'id' => $item->id,
                        'title' => $item->title,
                        'description' => $item->description,
                        'content' => $item->content,
                    ])
                ];
            })
            ->values();

        return response()->json($grouped);
    }

    // List all section names
    public function sections()
    {
        return response()->json(
            NestNotes::select('section')
                ->distinct()
                ->get()
                ->pluck('section')
                ->map(fn($section) => [
                    'section' => $section,
                    'slug' => Str::slug($section),
                ])
                ->values()
        );
    }

    // Get documents in a single section
    public function bySection($slug)
    {
        $sectionName = Str::of($slug)->replace('-', ' ')->title();

        $items = NestNotes::where('section', $sectionName)->get();

        if ($items->isEmpty()) {
            return response()->json(['message' => 'Section not found.'], 404);
        }

        return response()->json([
            'section' => $items->first()->section,
            'tagline' => $items->first()->tagline,
            'documents' => $items->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'content' => $item->content,
            ])
        ]);
    }


    //get document by id
    public function show($id)
    {
        $document = NestNotes::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document not found.'], 404);
        }

        return response()->json($document, 200);
    }


    //featured  recommended documents for home screen
    public static function recommended()
    {
        $recommended = NestNotes::where('is_featured', true)
            ->inRandomOrder()
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'tagline' => $item->tagline,
                    'description' => $item->description,
                    'section' => $item->section,
                    'slug' => $item->slug,
                    'content_preview' => Str::limit(strip_tags($item->content), 160),
                ];
            });

        return response()->json($recommended);
    }

    //challenge negative thoughts of my path
    public function showChallengeThought(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $daysSinceStart = optional($user->myPathStartedAt)->diffInDays(now()) ?? 0;
        $documentId = 10 + $daysSinceStart;

        $document = NestNotes::find($documentId);

        if (!$document) {
            return response()->json(['message' => 'Challenge document not found.'], 404);
        }

        return response()->json([
            'id' => $document->id,
            'title' => $document->title,
            'description' => $document->description,
            'content' => $document->content,
        ]);
    }
}
