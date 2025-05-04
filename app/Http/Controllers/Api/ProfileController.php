<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ProfileController extends ApiBaseController
{
    protected $user;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->user = new User();
    }

    public function index(Request $request)
    {
        $user = User::where('id', $this->userId)->first();

        $data = [
            'user_data' => $user->userdata(),
            'privacy'   => "https://zeacut.in/privacy"
        ];

        return $this->sendSuccessResponse($data, 'Success');
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|regex:/^[0-9]{10,15}$/|unique:users,phone,' . $this->userId,
            'place' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendErrorResponse($validator->errors()->first(), 403);
        }

        $user = User::find($this->userId);
        if (!$user) {
            return $this->sendErrorResponse('User not found', 404);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->place = $request->place;
        $user->save();

        return $this->sendSuccessResponse([], 'Profile updated successfully!');
    }

    public function update_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
            'pincode' => 'required|regex:/^[0-9]{6,}$/',
        ]);

        if ($validator->fails()) {
            return $this->sendErrorResponse($validator->errors()->first(), 403);
        }

        $user = User::find($this->userId);
        if (!$user) {
            return $this->sendErrorResponse('User not found', 404);
        }

        $user->address = $request->address;
        $user->pincode = $request->pincode;
        $user->save();

        return $this->sendSuccessResponse([], 'Adress updated successfully!');
    }
}
