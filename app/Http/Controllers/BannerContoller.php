<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banners;

class BannerContoller extends Controller
{
    public function index(){

        $data['list_items'] = Banners::get();
        $data['page_title'] = 'Banner';
        $data['page_name']  = 'admin.banner.index';
        return view('admin.main',$data);
    }

    public function add()
    {
        return view('admin.banner.add'); // Pass to view
    }

    public function submit(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $filePath = uploadFile($request->file('image'), 'banner-images');

        // Store Product
        $product = Banners::create([
            'title' => $request->title,
            'image' => $filePath,
        ]);

        return redirect()->route('product.index')->with('message_success', 'Banner added successfully!');
    }


    public function ajax_edit($id)
    {
        $data['edit_data'] = Banners::findOrFail($id);
        return view('admin.banner.edit', $data);
    }

    public function update(Request $request, $id)
{

    // Log::info('Product List Data', $_POST);

    $request->validate([
        'title' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);


    $data = [
        'title' => $request->title,
    ];


    // Handle Thumbnail Upload
    if ($request->hasFile('image')) {
        $filePath = uploadFile($request->file('image'), 'banner-images');
        $data['image'] = $filePath;
    }

    // Update Product Data
    $banner = Banners::findOrFail($id);
    $banner->update($data);


    return redirect()->route('banner.index')->with('message_success', 'Banner updated successfully!');
}



    public function delete($id)
    {
        $user = Banners::findOrFail($id);

        if ($user->delete()) {
            return redirect()->route('banner.index')->with('message_success', 'Banner deleted successfully!');
        } else {
            return redirect()->route('banner.index')->with('message_danger', 'Failed to delete user.');
        }
    }
}
