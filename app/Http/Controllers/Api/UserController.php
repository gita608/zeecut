<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiBaseController
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return $this->sendSuccessResponse([
                'user' => $user,
                'token' => $token
            ], 'Login successful');
        }

        return $this->sendErrorResponse('Invalid credentials', 401);
    }

    public function getUser(Request $request)
    {
        if (!$this->checkAuthToken()) {
            return $this->sendErrorResponse('Unauthorized access', 401);
        }

        return $this->sendSuccessResponse([
            'user' => $this->user
        ], 'User data retrieved successfully');
    }
}
