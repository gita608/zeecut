<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PincodeAccess;

class PincodeAccesController extends Controller
{
    public function index()
    {


        $data['list_items'] = PincodeAccess::get();
        $data['page_title'] = 'Pincode';
        $data['page_name'] = 'admin.pincode.index';
        return view('admin.main', $data);
    }

    public function ajax_add()
    {

        return view('admin.pincode.add');
    }

    public function submit(Request $request)
    {


        $request->validate([

            'name' => 'required|string',
            'pincode' => 'required|numeric|unique:pincode_access,pincode|max:6|min:1'
        ]);


        PincodeAccess::create([

            'name' => $request->name,
            'pincode' => $request->pincode

        ]);

        return redirect()->route(route: 'pincode.index')->with('message_success', 'Pincode Created!');

    }

    public function ajax_edit($id)
    {

        $data['edit_data'] = PincodeAccess::findOrFail($id);
        return view('admin.pincode.edit', $data);

    }

    public function update(Request $request, $id)
    {

        $data = $request->validate([

            'name' => 'required|string',
            'pincode' => 'required|numeric'
        ]);


        $pincode = PincodeAccess::findOrFail($id);
        $pincode->update($data);

        return redirect()->route('pincode.index')->with('message_success', 'Pincode Updated');
    }

    public function delete($id)
    {
        $user = PincodeAccess::findOrFail($id);

        if ($user->delete()) {
            return redirect()->route('pincode.index')->with('message_success', 'Product deleted successfully!');
        } else {
            return redirect()->route('pincode.index')->with('message_danger', 'Failed to delete user.');
        }
    }

}
