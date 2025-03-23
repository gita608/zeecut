<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
<<<<<<< HEAD
use App\Models\User;
use App\Models\PincodeAccess;
use Laravel\Sanctum\HasApiTokens;
=======
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PincodeAccess;
>>>>>>> rabil

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
<<<<<<< HEAD
            'email' => 'required|email',
            'phone' => 'required|regex:/^[0-9]{10,15}$/|unique:users',
=======
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|regex:/^[0-9]{10,15}$/|unique:users,phone',
>>>>>>> rabil
            'password' => 'required|string|min:6',
            'place' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
<<<<<<< HEAD
            'role_id' => 2,
=======
            'role_id' => 2, // Assuming default user role
>>>>>>> rabil
            'place' => $validatedData['place'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return $this->sendSuccessResponse([], 'User registered successfully', 201);
    }

    // ✅ Login User
    public function login(Request $request)
    {
        $validatedData = $request->validate([
<<<<<<< HEAD
            'phone' => ['required', 'regex:/^\+?[0-9]{10,15}$/'], // Accepts international format
=======
            'phone' => 'required|regex:/^\+?[0-9]{10,15}$/',
>>>>>>> rabil
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $validatedData['phone'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
<<<<<<< HEAD
            return $this->sendErrorResponse('Invalid credentials', 401);
=======
            return $this->sendErrorResponse('Invalid credentials', 403);
>>>>>>> rabil
        }

        return $this->sendSuccessResponse([
            'user' => $user->userdata(),
<<<<<<< HEAD
         ], 'Login successful');
=======
        ], 'Login successful');
>>>>>>> rabil
    }

    // ✅ Check Pincode Access
    public function pincode_access(Request $request)
    {
        $validatedData = $request->validate([
<<<<<<< HEAD
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
=======
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
>>>>>>> rabil
            'android_version' => '1.0.0',
            'ios_version' => '1.0.0',
            'force_android_version' => '1.0.0',
            'force_ios_version' => '1.0.0',
<<<<<<< HEAD
        ];
        return $this->sendSuccessResponse($data);
=======
        ]);
>>>>>>> rabil
    }

    // ✅ Logout User
    public function logout(Request $request)
    {
<<<<<<< HEAD
        $request->user()->tokens()->delete();
        return $this->sendSuccessResponse([], 'Logged out successfully');
=======
        if ($this->checkAuthToken()) {
            $request->user()->tokens()->delete();
            return $this->sendSuccessResponse([], 'Logged out successfully');
        }
        return $this->sendErrorResponse('Unauthorized', 401);
>>>>>>> rabil
    }
}
