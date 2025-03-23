<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PincodeAccess;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends ApiBaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    // ✅ Register User
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|regex:/^[0-9]{10,15}$/|unique:users',
            'password' => 'required|string|min:6',
            'place' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'role_id' => 2,
            'place' => $validatedData['place'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return $this->sendSuccessResponse([], 'User registered successfully', 201);
    }

    // ✅ Login User
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => ['required', 'regex:/^\+?[0-9]{10,15}$/'], // Accepts international format
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $validatedData['phone'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return $this->sendErrorResponse('Invalid credentials', 401);
        }

        // ✅ Generate API Token
        $token = $user->createToken('authToken')->plainTextToken;

        return $this->sendSuccessResponse([
            'user' => $user->userdata(),
            'token' => $token,
        ], 'Login successful');
    }

    // ✅ Check Pincode Access
    public function pincode_access(Request $request)
    {
        $validatedData = $request->validate([
            'pincode' => ['required', 'regex:/^[0-9]{6,}$/'],
        ]);

        $pincode = $validatedData['pincode'];
        $pincodeAccess = new PincodeAccess();
        $response = $pincodeAccess->getData(['pincode' => $pincode])->first();

        if ($response) {
            return $this->sendSuccessResponse([], 'Delivery is available for the entered pincode.');
        } else {
            return $this->sendErrorResponse('Delivery not available for the entered pincode.', 404);
        }
    }

    public function app_version()
    {
        $data = [
            'android_version' => '1.0.0',
            'ios_version' => '1.0.0',
            'force_android_version' => '1.0.0',
            'force_ios_version' => '1.0.0',
        ];
        return $this->sendSuccessResponse($data);
    }

    // ✅ Logout User
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->sendSuccessResponse([], 'Logged out successfully');
    }
}
