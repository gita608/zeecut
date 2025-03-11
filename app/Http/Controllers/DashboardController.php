<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
class DashboardController extends Controller
{
    public function index()
    {
        $data['students_count'] = User::where('role_id', 2)->count();
        // session()->flash('message_success', 'Welcome');


        $data['page_title'] = 'Dashboard';
        $data['page_name']  = 'admin.dashboard.index'; 
        return view('admin.main',$data); 
    }

}
