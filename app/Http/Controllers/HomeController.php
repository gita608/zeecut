<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function privacy()
    {
        $data['page_title'] = 'Privacy';
        return view('frontend.privacy', $data);
    }
    public function delete_account()
    {
        $data['page_title'] = 'Delete Account';
        return view('frontend.delete_account', $data);
    }
}
