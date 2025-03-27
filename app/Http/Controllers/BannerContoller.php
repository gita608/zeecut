<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BannerContoller extends Controller
{
    public function index(){

        $data['list_items'] = $this->category->getData();
        $data['page_title'] = 'Banner';
        $data['page_name']  = 'admin.banner.index';
        return view('admin.main',$data);
    }
}
