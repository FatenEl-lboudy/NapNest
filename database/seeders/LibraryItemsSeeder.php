<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\LibraryItems;

class LibraryItemsSeeder extends Seeder
{
    public function run(): void
    {
        $section = 'Understanding CBT';
        $slug = Str::slug($section);
        $tagline = 'Learn how your thoughts shape your sleep.';

        $documents = [
            [
                'title' => 'What is Cognitive Behavioral Therapy?',
                'description' => 'An overview of how CBT works to change thought patterns and behaviors.',
                'content' => "Cognitive Behavioral Therapy (CBT) is a talking therapy that focuses on how your thoughts, beliefs, and attitudes affect your feelings and behaviors. It helps identify unhelpful thought patterns and provides tools to challenge and replace them with more realistic and constructive ones.",
            ],
            [
                'title' => 'A Brief Introduction to CBT',
                'description' => 'Explains the CBT model with the cognitive triangle and behavioral case formulation.',
                'content' => "CBT is structured and time-limited. A core concept in CBT is the cognitive triangle: how thoughts, feelings, and behaviors influence one another. By working on just one part of this triangle—like replacing negative thoughts—you can shift emotional and behavioral outcomes.",
            ],
            [
                'title' => 'CBT vs. Medication for Sleep',
                'description' => 'Compares the effectiveness of CBT-I and sleep medications for treating insomnia.',
                'content' => "Research shows CBT-I (Cognitive Behavioral Therapy for Insomnia) is as effective—if not more so—than sleep medications over the long term. While medications may offer quick relief, CBT-I provides sustainable changes by targeting underlying causes of sleep problems.",
            ]
        ];

        foreach ($documents as $doc) {
            LibraryItems::create([
                'title' => $doc['title'],
                'tagline' => $tagline,
                'description' => $doc['description'],
                'content' => $doc['content'],
                'section' => $section,
                'slug' => $slug,
                'is_featured' => true,
            ]);
        }
        $section = 'CBT Tools & Techniques';
        $slug = Str::slug($section);
        $tagline = 'Practical strategies to shift thoughts and reclaim rest.';

        $documents = [
            [
                'title' => 'The Cognitive Triangle: Thoughts, Feelings, Behaviors',
                'description' => 'A foundational CBT model that helps users understand how their thoughts influence emotions and actions.',
                'content' => 'The cognitive triangle is a core concept in CBT. It shows how our thoughts, feelings, and behaviors are interconnected...'
            ],
            [
                'title' => 'Thought Records: Catching and Challenging Negative Thoughts',
                'description' => 'A guided worksheet-style explanation of how to log and reframe unhelpful thoughts.',
                'content' => 'Thought records are a CBT tool used to track automatic negative thoughts and challenge them with evidence...'
            ],
            [
                'title' => 'Cognitive Restructuring: Rewriting the Story',
                'description' => 'A step-by-step guide to replacing unhelpful beliefs with more realistic ones.',
                'content' => 'Cognitive restructuring is the process of identifying irrational or unhelpful thoughts and replacing them...'
            ]
        ];

        foreach ($documents as $doc) {
            LibraryItems::create([
                'title' => $doc['title'],
                'tagline' => $tagline,
                'description' => $doc['description'],
                'content' => $doc['content'],
                'section' => $section,
                'slug' => $slug,
                'is_featured' => false,
            ]);
        }
        $section = 'Sleep Education';
        $slug = Str::slug($section);
        $tagline = 'Understand how sleep works—and how to work with it.';

        $documents = [
            [
                'title' => 'How Sleep Works: The Science Behind Rest',
                'description' => 'Explains sleep cycles, circadian rhythms, and why deep sleep matters.',
                'content' => 'Sleep is not just “shutting off”—it’s an active, restorative process...'
            ],
            [
                'title' => 'The 3P Model of Insomnia',
                'description' => 'A CBT-I framework that explains how sleep problems begin and persist.',
                'content' => 'The 3P Model breaks insomnia into three parts: Predisposing, Precipitating, Perpetuating...'
            ],
            [
                'title' => 'Sleep Hygiene: Habits That Help You Sleep Better',
                'description' => 'A checklist of evidence-based behaviors that support healthy sleep.',
                'content' => 'Good sleep hygiene means creating a sleep-friendly environment and routine...'
            ]
        ];

        foreach ($documents as $doc) {
            LibraryItems::create([
                'title' => $doc['title'],
                'tagline' => $tagline,
                'description' => $doc['description'],
                'content' => $doc['content'],
                'section' => $section,
                'slug' => $slug,
                'is_featured' => false,
            ]);
        }
        $section = 'Managing Sleep Problems';
        $slug = Str::slug($section);
        $tagline = 'Real solutions for restless nights.';

        $documents = [
            [
                'title' => 'Racing Thoughts at Bedtime: How to Slow the Mind',
                'description' => 'A CBT-based guide to managing intrusive thoughts that keep you awake.',
                'content' => 'Racing thoughts are a common barrier to falling asleep. CBT teaches that trying to suppress these thoughts often backfires...'
            ],
            [
                'title' => 'Early Morning Waking: What to Do When You’re Up Too Soon',
                'description' => 'Strategies for handling premature waking without reinforcing insomnia.',
                'content' => 'Waking up too early and not falling back asleep can be frustrating. Here’s what CBT-I recommends...'
            ],
            [
                'title' => 'Bedtime Anxiety: Breaking the Cycle of Dread',
                'description' => 'A CBT-I approach to reducing anticipatory anxiety about sleep.',
                'content' => 'If you dread bedtime because you expect another sleepless night, CBT-I can help break that cycle...'
            ]
        ];

        foreach ($documents as $doc) {
            LibraryItems::create([
                'title' => $doc['title'],
                'tagline' => $tagline,
                'description' => $doc['description'],
                'content' => $doc['content'],
                'section' => $section,
                'slug' => $slug,
                'is_featured' => false,
            ]);
        }
        $section = 'Breathing & Relaxation';
        $slug = Str::slug($section);
        $tagline = 'Breathe your way into calmer sleep.';

        $documents = [
            [
                'title' => 'Box Breathing: Calm in Four Simple Steps',
                'description' => 'A structured breathing technique to reduce anxiety and promote focus.',
                'content' => 'Box breathing is a simple yet powerful technique used by athletes, soldiers, and therapists...'
            ],
            [
                'title' => '4-7-8 Breathing: A Natural Sleep Aid',
                'description' => 'A guided breathing pattern that slows the heart rate and eases you into sleep.',
                'content' => 'The 4-7-8 technique is designed to activate your parasympathetic nervous system...'
            ],
            [
                'title' => 'Progressive Muscle Relaxation: Release Tension from Head to Toe',
                'description' => 'A body-based relaxation method that pairs well with breathing exercises.',
                'content' => 'Progressive Muscle Relaxation (PMR) involves tensing and relaxing muscle groups one at a time...'
            ]
        ];

        foreach ($documents as $doc) {
            LibraryItems::create([
                'title' => $doc['title'],
                'tagline' => $tagline,
                'description' => $doc['description'],
                'content' => $doc['content'],
                'section' => $section,
                'slug' => $slug,
                'is_featured' => false,
            ]);
        }
    }
}
