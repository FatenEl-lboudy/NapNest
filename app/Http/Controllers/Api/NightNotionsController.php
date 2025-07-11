<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CbtFlashcards;
use App\Models\CbtTechnique;

class NightNotionsController extends Controller
{
    public function questions()
    {
        $questions = CbtFlashcards::select('id', 'negative_thought')
            ->inRandomOrder()
            ->take(3)
            ->get();

        return response()->json($questions);
    }

    public function flashcards($flashcard_id)
    {
        $cards = CbtFlashcards::where('id', $flashcard_id)->get([
            'id',
            'negative_thought',
            'positive_reframe'
        ]);

        return response()->json($cards);
    }
}
