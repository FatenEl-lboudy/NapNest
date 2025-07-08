<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SleepTune;

class SleepTuneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tunes = [
             
            [
                'title' => 'Ocean Waves',
                'description' => 'Rolling ocean waves with distant seagulls.',
                'source_type' => 'local',
                'file_path' => 'audio/ocean_waves.mp3',
                'youtube_url' => null,
                'duration' => 600,
                'is_featured' => True
            ],
            [
                'title' => 'Thunderstorms',
                'description' => 'Gentle rain layered with distant thunder to quiet the mind.',
                'source_type' => 'local',
                'file_path' => 'audio/Thunderstorms.mp3',
                'youtube_url' => null,
                'duration' => 600, 
                'is_featured' => true
            ],
            [
                'title' => 'Rainfall',
                'description' => 'The rhythmic sound of rain calms the mind before sleep.',
                'source_type' => 'local',
                'file_path' => 'audio/rainfall.mp3',
                'youtube_url' => null,
                'duration' => 600,
                'is_featured' => true
            ],
            [
                'title' => 'Fireplace Crackling',
                'description' => 'The soft crackle of fire induces comfort and security.',
                'source_type' => 'local',
                'file_path' => 'audio/Fireplace Crackling.mp3',
                'youtube_url' => null,
                'duration' => 600,
                'is_featured' => true
            ],
            [
                'title' => 'Forest Ambience',
                'description' => 'Rustling leaves and birdsong that gently ground the mind.',
                'source_type' => 'local',
                'file_path' => 'audio\Forest Ambience.mp3',
                'youtube_url' => null,
                'duration' => 615,
                'is_featured' => True
            ],
            [
                'title' => 'White Noise',
                'description' => 'A stable soundscape that masks anxiety-triggering noises.',
                'source_type' => 'youtube',
                'file_path' => null,
                'youtube_url' => 'https://youtu.be/Og40mpl8VNc?si=p5pFImvnh64HDRy7',
                'duration' => null,
                'is_featured' => false
            ]
        ];

        SleepTune::insert($tunes);
    
    }
}
