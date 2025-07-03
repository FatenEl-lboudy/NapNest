<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PSQITest;
use App\Models\User;

class PSQIController extends Controller
{
    public function getQuestions()
    {
        $path = resource_path('json/psqi_questions.json');

        if (!file_exists($path)) {
            return response()->json(['error' => 'Questions file not found'], 404);
        }

        $jsonData = file_get_contents($path);
        $data = json_decode($jsonData, true);

        return response()->json($data);
    }

    public function submitAnswers(Request $request, $patient_id)
    {
        $patient = User::findOrFail($patient_id);
        $validated = $request->validate([
            'answers' => 'required|array',
        ]);

        $answers = $validated['answers'];

        // calculatePSQI
        $calculation = $this->calculatePSQI($answers);

        // Add custom message
        $message = '';
        switch ($calculation['status']) {
            case 'Moderate':
                $message = 'Your sleep could be improved. We encourage you to continue using the app for better habits.';
                break;
            case 'Severe':
                $message = 'We strongly recommend consulting a doctor to address your condition and improve your sleep health.';
                break;
        }

        $psqiTest = PSQITest::create([
            'patient_id'             => $patient->patient_id,
            'score'                  => $calculation['global_score'],
            'status'                 => $calculation['status'],
            'sleep_quality'          => $calculation['C1'], 
            'sleep_latency'          => $calculation['C2'],
            'sleep_duration'         => $calculation['C3'], 
            'sleep_efficiency'       => $calculation['C4'], 
            'sleep_disturbances'     => $calculation['C5'], 
            'use_of_sleep_medication'=> $calculation['C6'], 
            'daytime_dysfunction'    => $calculation['C7'], 
            'answers'                => json_encode($answers)
        ]);

        $psqiTest->makeHidden('answers');

        return response()->json([
            'message'       => 'PSQI test saved successfully',
            'status' => $calculation['status'],
            'score'         => $calculation['global_score'],
            'advice'        => $message,
            'data'          => [
        'sleep_quality'           => $psqiTest->sleep_quality,
        'sleep_latency'           => $psqiTest->sleep_latency,
        'sleep_duration'          => $psqiTest->sleep_duration,
        'sleep_efficiency'        => $psqiTest->sleep_efficiency,
        'sleep_disturbances'      => $psqiTest->sleep_disturbances,
        'use_of_sleep_medication' => $psqiTest->use_of_sleep_medication,
        'daytime_dysfunction'     => $psqiTest->daytime_dysfunction,
    ]
        ], 201);
    }

    public function getResults($patient_id)
    {
        $patient = User::findOrFail($patient_id);
        $results = $patient->psqiTests()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'results' => $results
        ]);
    }

    private function calculatePSQI($answers)
    {
        $C1 = isset($answers['q9']) ? intval($answers['q9']) : 0;

        // C2: Sleep Latency
        $latencyScore = 0;
        if (isset($answers['q2'])) {
            switch ($answers['q2']) {
                case '1-15':  $latencyScore = 0; break;
                case '16-30': $latencyScore = 1; break;
                case '31-60': $latencyScore = 2; break;
                case '+60':   $latencyScore = 3; break;
                default:      $latencyScore = 0; break;
            }
        }
        $latencyFreq = isset($answers['q5_1']) ? intval($answers['q5_1']) : 0;
        $sumLatency = $latencyScore + $latencyFreq;
        $C2 = ($sumLatency == 0) ? 0 : (($sumLatency <= 2) ? 1 : (($sumLatency <= 4) ? 2 : 3));

        // C3: Sleep Duration
        if (isset($answers['q4'])) {
            switch ($answers['q4']) {
                case ">=7":  $C3 = 0; break;
                case "6-7":  $C3 = 1; break;
                case "5-6":  $C3 = 2; break;
                case "<5":   $C3 = 3; break;
                default:     $C3 = 0; break;
            }
        } else {
            $C3 = 0;
        }

        // C4: Habitual Sleep Efficiency
        $bedHour = 22;
        if (isset($answers['q1'])) {
            switch ($answers['q1']) {
                case '2am-4am':     $bedHour = 3; break;
                case '12am-2am':    $bedHour = 1; break;
                case '10pm-12am':   $bedHour = 23; break;
                case '8pm-10pm':    $bedHour = 21; break;
                default:            $bedHour = 22; break;
            }
        }
        $wakeHour = 7;
        if (isset($answers['q3'])) {
            switch ($answers['q3']) {
                case '6-8am':   $wakeHour = 7; break;
                case '8-10am':  $wakeHour = 9; break;
                case '10-12pm': $wakeHour = 11; break;
                case '12-2pm':  $wakeHour = 13; break;
                default:        $wakeHour = 7; break;
            }
        }
        $timeInBed = ($bedHour < $wakeHour) ? $wakeHour - $bedHour : ($wakeHour + 24) - $bedHour;
        $actualSleep = 7;
        if (isset($answers['q4'])) {
            switch ($answers['q4']) {
                case ">=7": $actualSleep = 7.5; break;
                case "6-7": $actualSleep = 6.5; break;
                case "5-6": $actualSleep = 5.5; break;
                case "<5":  $actualSleep = 4.5; break;
            }
        }
        $effPercent = ($timeInBed > 0) ? ($actualSleep / $timeInBed) * 100 : 0;
        $C4 = ($effPercent >= 85) ? 0 : (($effPercent >= 75) ? 1 : (($effPercent >= 65) ? 2 : 3));

        // C5: Disturbances
        $distSum = 0;
        foreach (['q5_2','q5_3','q5_4','q5_5','q5_6','q5_7','q5_8','q5_9'] as $dq) {
            $distSum += isset($answers[$dq]) ? intval($answers[$dq]) : 0;
        }
        $C5 = ($distSum == 0) ? 0 : (($distSum <= 9) ? 1 : (($distSum <= 18) ? 2 : 3));

        // C6: Medication
        $C6 = isset($answers['q6']) ? intval($answers['q6']) : 0;

        // C7: Daytime Dysfunction
        $daySum = (isset($answers['q7']) ? intval($answers['q7']) : 0) + (isset($answers['q8']) ? intval($answers['q8']) : 0);
        $C7 = ($daySum == 0) ? 0 : (($daySum <= 2) ? 1 : (($daySum <= 4) ? 2 : 3));

        $global = $C1 + $C2 + $C3 + $C4 + $C5 + $C6 + $C7;
        $status = ($global < 16) ? 'Moderate' : 'Severe';

        return [
            'global_score' => $global,
            'status'       => $status,
            'C1' => $C1, 'C2' => $C2, 'C3' => $C3,
            'C4' => $C4, 'C5' => $C5, 'C6' => $C6, 'C7' => $C7
        ];
    }
}
