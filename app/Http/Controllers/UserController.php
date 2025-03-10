<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function index()
    {
        $data['list_items'] = $this->userModel->getData(['role_id' => 2]);
        $data['page_title'] = 'Users';
        $data['page_name'] = 'admin.user.index';

        return view('admin.main', $data);
    }

    public function ajax_add()
    {
        return view('admin.user.add');
    }

    public function submit(Request $request)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|digits_between:10,15',
            'email' => 'required|email|unique:users,email',
        ]);

        // Insert data into database
        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->phone),
        ]);

        return redirect()->route('user.index')->with('message_success', 'User added successfully!');
    }

    public function ajax_edit($id)
    {
        $data['edit_data'] = User::findOrFail($id);
        return view('admin.user.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|digits_between:10,15',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return redirect()->route('user.index')->with('message_success', 'User updated successfully!');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id); 

        if ($user->delete()) {
            return redirect()->route('user.index')->with('message_success', 'User deleted successfully!');
        } else {
            return redirect()->route('user.index')->with('message_danger', 'Failed to delete user.');
        }
    }

    




}
