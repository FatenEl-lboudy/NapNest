<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {
    
    //  Register 
    public function register(Request $request)
{
    $request->validate([
        'patient_name' => 'required|string|max:50',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'gender' => 'required|in:Male,Female', 
        'birth_date' => 'required|date', 
    ]);

    $patient = User::create([
        'patient_name' => $request->patient_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'gender' => $request->gender, 
        'birth_date' => $request->birth_date,
    ]);

    $token = $patient->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Registration successful!',
        'patient' => $patient,
    ], 201);
}

    
    // Login
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json([
                'message' => 'Email is incorrect.'
            ], 401); 
        }
    
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Password is incorrect.'
            ], 401); 
        }
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'message' => 'Welcome ' . $user->patient_name,
            'token' => $token,
        ], 200);
    }
    

    // Logout
    public function logout(Request $request) {
        $request->user()->tokens()->delete(); 
        return response()->json(['message' => 'Logged out successfully.']);
    }
}
