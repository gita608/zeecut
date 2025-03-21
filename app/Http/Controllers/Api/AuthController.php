<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends ApiBaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        }

    // ✅ Register User
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^\+?[0-9]{10,15}$/'], // Accepts international format and 10-15 digits
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 0, 'message' => 'Invalid credentials', 'data' => []], 201);
        }

        return response()->json([
            'status' => 1,
            'message' => 'Login Successful',
            'data' => $user->userdata()
        ], 200);
    }


    public function user(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }

    // ✅ Logout User (Invalidate Token)
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
