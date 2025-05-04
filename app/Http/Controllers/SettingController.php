<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function index(){

        $data['delivery_charge'] = Setting::where('key','delivery_charge')->first()->value;
        $data['page_title'] = 'Settings';
        $data['page_name'] = 'admin.setting.index';

        return view('admin.main', $data);
    }


    public function update(Request $request){


        $request->validate([
            'delivery_charge' => 'required|int'
        ]);

        $delivery_charge = Setting::where('key','delivery_charge')->first();

        // echo"<pre>";
        // print_r($delivery_charge);die();

        $delivery_charge->update(['value' => $request->delivery_charge]);


        return redirect()->route('setting.index')->with('message_success', 'Setting Updated Successfully!');
    }


}
