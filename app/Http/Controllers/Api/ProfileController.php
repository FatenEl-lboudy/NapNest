<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
    
        return response()->json([
            'user' => [
                'patient_name' => $user->patient_name,
                'email'        => $user->email,
                'birth_date'   => $user->birth_date,
                'gender'       => $user->gender,
                
            ]
        ], 200);
    }
    
    public function update(Request $request)
{
    $user = $request->user();

    $request->validate([
        'patient_name' => 'sometimes|string|max:50',
        'email' => [
            'sometimes',
            'email',
            Rule::unique('users')->ignore($user->id),
        ],
        'gender' => 'sometimes|in:Male,Female',
        'birth_date' => 'sometimes|date',
    ]);
#Update Profile 
    $user->update($request->only([
        'patient_name',
        'email',
        'gender',
        'birth_date',
    ]));

    return response()->json([
        'message' => 'Profile updated successfully',
        'user' => [
        'patient_name' => $user->patient_name,
        'email'        => $user->email,
        'gender'       => $user->gender,
        'birth_date'   => $user->birth_date,
    ]
    ], 200);
}

    public function destroy(Request $request)
{
    $user = $request->user();
    $user->delete();

    return response()->json(['message' => 'Account deleted successfully.'], 200);
}

}
