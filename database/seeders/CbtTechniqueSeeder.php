<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CbtTechnique;

class CbtTechniqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $techniques = [
            [
                'title' => '4-4-4-4 Breathing (Box Breathing)',
                'type' => 'breathing',
                'description' => 'Inhale through your nose for 4 counts. Hold for 4 counts. Exhale through your mouth for 4 counts. Hold again for 4 counts. Repeat the cycle steadily for 3–10 minutes.',
                'benefits' => 'Enhances focus, lowers heart rate, and calms the nervous system. Often used in high-stress environments.',
                'resource_path' => null
            ],
            [
                'title' => '7-11 Breathing',
                'type' => 'breathing',
                'description' => 'Inhale deeply through your nose for 7 counts. Exhale slowly and completely through your mouth for 11 counts. Maintain a steady rhythm where the exhale is longer than the inhale.',
                'benefits' => 'Reduces anxiety, slows heart rate, and promotes relaxation. Great for managing stress quickly.',
                'resource_path' => null
            ],
            [
                'title' => '3-6 Breathing',
                'type' => 'breathing',
                'description' => 'Inhale through your nose for 3 counts. Exhale through your mouth for 6 counts. Focus on extending the exhale to increase calm.',
                'benefits' => 'Quickly calms the mind, helps regulate emotions, and is easy to practice in stressful situations.',
                'resource_path' => null
            ],
            [
                'title' => '5-5 Breathing (Resonant Breathing)',
                'type' => 'breathing',
                'description' => 'Inhale deeply through your nose for 5 counts. Exhale gently through your nose for 5 counts. Maintain this steady rhythm throughout the session.',
                'benefits' => 'Synchronizes breathing with heart rate, improves emotional balance, and promotes overall relaxation.',
                'resource_path' => null
            ],
            [
                'title' => '1-2 Breathing',
                'type' => 'breathing',
                'description' => 'Inhale through your nose for a set number of counts (e.g. 3). Exhale for twice as long (e.g. 6). Keep the exhale smooth and relaxed to emphasize its calming effect.',
                'benefits' => 'Stimulates the parasympathetic nervous system, reduces tension, and improves breath control.',
                'resource_path' => null
            ],
            [
                'title' => '4-6 Breathing',
                'type' => 'breathing',
                'description' => 'Sit or lie down comfortably. Inhale deeply through your nose for 4 counts, filling your lungs. Exhale slowly through your mouth for 6 counts, letting all the air out. Repeat steadily.',
                'benefits' => 'Helps reduce stress, calm the mind, and lower heart rate.',
                'resource_path' => null
            ]
        ];

        CbtTechnique::insert($techniques);
    }

    }

