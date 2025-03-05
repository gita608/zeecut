<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\support\Facades\Hash;
use App\Http\Requests\ValidateRegister;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register() 
    {
        if (Auth::check() && Auth::user()->role == 1) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.register');
    }


    public function login() 
    {
        if (Auth::check() && Auth::user()->role == 1) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function verify(Request $request)
    {
        // $loginCredentials = $request->only('username', 'password');
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, hashedValue: $user->password)) {
            // dd($user);
            Auth::login($user);
            if(Auth::user()->role_id == '1'){
                return redirect()->route('admin.dashboard');
            }
        }
        return back()->with('error', 'Invalid login credentials...');
    }
    
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    }
}
