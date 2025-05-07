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
            'patient_name' => 'required|string|max:50',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'gender' => 'required|in:Male,Female',
            'birth_date' => 'required|date',
        ]);

        // Update personal Information 
        $user->update([
            'patient_name' => $request->patient_name,
            'email' => $request->email,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ], 200);
        
    }
    public function destroy(Request $request)
{
    $user = $request->user();
    $user->delete();

    return response()->json(['message' => 'Account deleted successfully.'], 200);
}

}
