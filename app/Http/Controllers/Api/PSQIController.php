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
        $psqiTest = PSQITest::create([
            'patient_id'                   => $patient->patient_id,
            'score'                        => $calculation['global_score'],
            'status'                       => $calculation['status'],
            'sleep_quality'     => $calculation['C1'], 
            'sleep_latency'                => $calculation['C2'],
            'sleep_duration'               => $calculation['C3'], 
            'sleep_efficiency'    => $calculation['C4'], 
            'sleep_disturbances'           => $calculation['C5'], 
            'use_of_sleep_medication'      => $calculation['C6'], 
            'daytime_dysfunction'          => $calculation['C7'], 
            'answers'                      => json_encode($answers)
        ]);
        $psqiTest->makeHidden('answers');

        return response()->json([
            'message' => 'PSQI test saved successfully',
            'data'    => $psqiTest
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

    /**
     *PSQI (Scoring) 
     *
     * C1: Subjective Sleep Quality       => (q9)
     * C2: Sleep Latency                  => (q2 + q5_1)
     * C3: Sleep Duration                 => (q4)
     * C4: Habitual Sleep Efficiency      => (q1 + q3 + q4) 
     * C5: Sleep Disturbances             => (q5_2 .. q5_9)
     * C6: Use of Sleep Medication        => (q6)
     * C7: Daytime Dysfunction            => (q7 + q8)
     */
    private function calculatePSQI($answers)
{
    $C1 = isset($answers['q9']) ? intval($answers['q9']) : 0;

    // C2: Sleep Latency (q2 + q5_1)
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
    if ($sumLatency == 0) {
        $C2 = 0;
    } elseif ($sumLatency <= 2) {
        $C2 = 1;
    } elseif ($sumLatency <= 4) {
        $C2 = 2;
    } else {
        $C2 = 3;
    }

    // C3: Sleep Duration (q4)
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

    // C4: Habitual Sleep Efficiency (using Q1, Q3, and Q4)
    // Q1: Bedtime
    $bedHour = 22; 
    if (isset($answers['q1'])) {
        switch ($answers['q1']) {
            case '2am-4am':
                $bedHour = 3;
                break;
            case '12am-2am':
                $bedHour = 1;
                break;
            case '10pm-12am':
                $bedHour = 23;
                break;
            case '8pm-10pm':
                $bedHour = 21;
                break;
            default:
                $bedHour = 22;
                break;
        }
    }
    // Q3: Wake-up Time
    $wakeHour = 7; 
    if (isset($answers['q3'])) {
        switch ($answers['q3']) {
            case '6-8am':
                $wakeHour = 7;
                break;
            case '8-10am':
                $wakeHour = 9;
                break;
            case '10-12pm':
                $wakeHour = 11;
                break;
            case '12-2pm':
                $wakeHour = 13;
                break;
            default:
                $wakeHour = 7;
                break;
        }
    }
    if ($bedHour < $wakeHour) {
        $timeInBed = $wakeHour - $bedHour;
    } else {
        $timeInBed = ($wakeHour + 24) - $bedHour;
    }
    
    $actualSleep = 7; 
    if (isset($answers['q4'])) {
        switch ($answers['q4']) {
            case ">=7": $actualSleep = 7.5; break;
            case "6-7": $actualSleep = 6.5; break;
            case "5-6": $actualSleep = 5.5; break;
            case "<5":  $actualSleep = 4.5; break;
            default:    $actualSleep = 7; break;
        }
    }
    if ($timeInBed > 0) {
        $effPercent = ($actualSleep / $timeInBed) * 100;
        if ($effPercent >= 85) {
            $C4 = 0;
        } elseif ($effPercent >= 75) {
            $C4 = 1;
        } elseif ($effPercent >= 65) {
            $C4 = 2;
        } else {
            $C4 = 3;
        }
    } else {
        $C4 = 0;
    }

    // C5: Sleep Disturbances (q5_2 to q5_9)
    $distSum = 0;
    $distQuestions = ['q5_2','q5_3','q5_4','q5_5','q5_6','q5_7','q5_8','q5_9'];
    foreach ($distQuestions as $dq) {
        if (isset($answers[$dq])) {
            $distSum += intval($answers[$dq]);
        }
    }
    if ($distSum == 0) {
        $C5 = 0;
    } elseif ($distSum <= 9) {
        $C5 = 1;
    } elseif ($distSum <= 18) {
        $C5 = 2;
    } else {
        $C5 = 3;
    }

    // C6: Use of Sleep Medication (q6)
    $C6 = isset($answers['q6']) ? intval($answers['q6']) : 0;

    // C7: Daytime Dysfunction (q7 + q8)
    $daySum = 0;
    if (isset($answers['q7'])) $daySum += intval($answers['q7']);
    if (isset($answers['q8'])) $daySum += intval($answers['q8']);
    if ($daySum == 0) {
        $C7 = 0;
    } elseif ($daySum <= 2) {
        $C7 = 1;
    } elseif ($daySum <= 4) {
        $C7 = 2;
    } else {
        $C7 = 3;
    }

    $global = $C1 + $C2 + $C3 + $C4 + $C5 + $C6 + $C7;
    if ($global < 5) {
        $status = 'Good';
    } elseif ($global < 16) {
        $status = 'Moderate';
    } else {
        $status = 'Severe';
    }

    return [
        'global_score' => $global,
        'status'       => $status,
        'C1'           => $C1,
        'C2'           => $C2,
        'C3'           => $C3,
        'C4'           => $C4,
        'C5'           => $C5,
        'C6'           => $C6,
        'C7'           => $C7,
    ];
}
}

