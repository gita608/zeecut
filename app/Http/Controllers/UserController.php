<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function index()
    {
        $data['list_items'] = $this->userModel->get(['role_id' => 2]);
        $data['page_title'] = 'Users';
        $data['page_name']  = 'admin.user.index'; 

        return view('admin.main', $data); 
    }
}
