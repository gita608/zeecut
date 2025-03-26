<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PincodeAccess;

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
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|regex:/^[0-9]{10,15}$/|unique:users,phone',
            'password' => 'required|string|min:6',
            'place' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'role_id' => 2, // Assuming default user role
            'place' => $validatedData['place'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return $this->sendSuccessResponse([], 'User registered successfully', 201);
    }

    // ✅ Login User
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => 'required|regex:/^\+?[0-9]{10,15}$/',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $validatedData['phone'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return $this->sendErrorResponse('Invalid credentials', 403);
        }

        // Generate the token using the user instance
        $token = $user->generateToken();

        $user_data = $user->userdata();
        $user_data['token'] = $token; // Assign the token to user data

        return $this->sendSuccessResponse([
            'user' => $user_data,
        ], 'Login successful');
    }

    // ✅ Check Pincode Access
    public function pincode_access(Request $request)
    {
        $validatedData = $request->validate([
            'pincode' => 'required|regex:/^[0-9]{6,}$/',
        ]);

        $pincodeAccess = PincodeAccess::where('pincode', $validatedData['pincode'])->first();

        if (!$pincodeAccess) {
            return $this->sendErrorResponse('Delivery not available for the entered pincode.', 200);
        }

        return $this->sendSuccessResponse([], 'Delivery is available for the entered pincode.');
    }

    // ✅ App Version Info
    public function app_version()
    {
        return $this->sendSuccessResponse([
            'android_version' => '1.0.0',
            'ios_version' => '1.0.0',
            'force_android_version' => '1.0.0',
            'force_ios_version' => '1.0.0',
        ]);
    }

    // ✅ Logout User
    public function logout(Request $request)
    {
        if ($this->checkAuthToken()) {
            $request->user()->tokens()->delete();
            return $this->sendSuccessResponse([], 'Logged out successfully');
        }
        return $this->sendErrorResponse('Unauthorized', 401);
    }
}
