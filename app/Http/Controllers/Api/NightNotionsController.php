<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CbtFlashcards;
use App\Models\CbtTechnique;

class NightNotionsController extends Controller
{
    public function questions()
    {
        $questions = CbtTechnique::where('type', 'flashcard')
            ->select('id', 'title', 'description')
            ->get();

        return response()->json($questions);
    }

    public function flashcards($techniqueId)
    {
        $cards = CbtFlashcards::where('cbt_technique_id', $techniqueId)->get([
            'id', 'negative_thought', 'positive_reframe'
        ]);

        return response()->json($cards);
    }
}
