<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MyPath;
use App\Models\NestNotes;
use App\Models\CbtTechnique;
use App\Models\CbtFlashcards;
use App\Models\User;
use Carbon\Carbon;

class MyPathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        MyPath::create([
            'patient_id' => 7,
            'title' => 'Your Daily Sleep Path',
            'instructions' => 'A balanced flow of calm learning, gentle breathing, and thought clarity.',
            'day_index' => 1,
            'scheduled_for' => Carbon::now()
        ]);
        NestNotes::create([
            'title' => 'How Sleep Shapes Your Mind',
            'tagline' => 'Understand why rest restores more than just your body.',
            'section' => 'Light Learn',
            'description' => 'This article walks through how sleep impacts emotions, decision-making, and mental clarity.',
            'content' => 'Sleep helps consolidate memories, rebalance neurotransmitters, and regulate cortisol.',
            'is_featured' => true,
            'slug' => 'how-sleep-shapes-your-mind'
        ]);
        CbtTechnique::create([
            'title' => 'Box Breathing',
            'type' => 'breathing',
            'description' => 'Inhale for 4, hold 4, exhale 4, hold 4. Repeat slowly.',
            'benefits' => 'Lowers stress hormones and anchors focus.',
        ]);
        $technique = CbtTechnique::create([
            'title' => 'Reframe Catastrophic Thinking',
            'type' => 'flashcard',
            'description' => 'Challenge thoughts like "everything will go wrong" with balanced realism.',
        ]);
        CbtFlashcards::insert([
            [
                'cbt_technique_id' => $technique->id,
                'negative_thought' => "What if I mess everything up tomorrow?",
                'positive_reframe' => "It’s okay to feel unsure—progress is not perfection.",
            ],
            [
                'cbt_technique_id' => $technique->id,
                'negative_thought' => "If I don’t fall asleep now, tomorrow’s ruined.",
                'positive_reframe' => "One rough night won’t define the whole day.",
            ]
        ]);
    }
}
