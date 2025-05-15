<?php

namespace App\Http\Controllers;

use App\Models\PayLater;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class PayLaterController extends Controller
{



    public function index(){

        $data['list_items'] = PayLater::with('user')->get();
        $data['page_title'] = 'Pay Later Users';
        $data['page_name']  = 'admin.PayLater.index';
        return view('admin.main',$data);
    }

    public function ajax_add()
    {
        $payLaterUserIds = PayLater::pluck('user_id')->toArray();

        $users = User::where('role_id', 2)
        ->whereNotIn('id',$payLaterUserIds)
        ->get();
        return view('admin.PayLater.add',compact('users'));
    }

    public function submit(Request $request){

        $data = $request->validate([

            'user_id' => 'required|numeric',
            'credit_limit' => 'required|numeric'
        ]);


        PayLater::create($data);

        return redirect(route('payLater.index'))->with('message_success','Sucessfully Added User');
    }

    public function ajax_edit(Request $request,$id){
        $edit_data = PayLater::where('id',$id)->first();
        return view('admin.PayLater.edit',compact('edit_data'));
    }

    public function update(Request $request,$id){

        $request->validate([
            'credit_limit' => 'required|numeric'
        ]);
        
        $payLater = PayLater::findOrFail($id);

        $data['credit_limit'] = $request->credit_limit + $payLater->credit_limit;

        $payLater->update($data);

        return redirect(route('payLater.index'))->with('message_success','Sucessfully Updated Credit');
    }


    public function toggleStatus(Request $request)
    {
        $payLater = PayLater::find($request->id);

        if ($payLater) {
            $payLater->status = $request->status;
            $payLater->save();

            return response()->json(['message' => 'Status updated successfully.']);
        }

        return response()->json(['message' => 'Item not found.'], 404);
    }

}
