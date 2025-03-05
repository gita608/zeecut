<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;  // To use the storage facade for image upload
use Illuminate\Support\Facades\DB;
class CategoriesConroller extends Controller
{
    public function index()
    {
        $data['list_items'] = Category::get();

        $data['page_title'] = 'Category';
        $data['page_name'] = 'admin.category.index';
        return view('admin.main', $data);
    }

    public function add()
    {

        $data = [];
        return view('admin.category.ajax_add', $data);
    }

    public function submit(Request $request)
    {
        // DB::enableQueryLog();

        // Validate the form data
        $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle the file upload
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            // Call the uploadFile function
            $filePath = $this->uploadFile($file, 'uploads', 'public');
        }

        // Create the category entry with title and file path
        $data['title'] = $request->title;
        $data['thumbnail'] = $filePath;

        // Save the category to the database
        Category::create($data);
        // dd(DB::getQueryLog());
        return redirect(route('categories.index'))->with('message_success', 'Created Successfully!');
    }

    public function edit($id)
    {

        $data['edit_data'] = Category::findOrFail($id);
        return view('admin.category.ajax_edit', $data);
    }


    

    public function update(Request $request,$id)
    {
        // DB::enableQueryLog();

        // Validate the form data
        $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle the file upload
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            // Call the uploadFile function
            $filePath = $this->uploadFile($file, 'uploads', 'public');
        }

        // Create the category entry with title and file path
        $data['title'] = $request->title;
        $data['thumbnail'] = $filePath ?? '' ;
        $category = Category::findOrFail($id);    
        $category->update($data);
        return redirect(route('categories.index'))->with('message_success', 'Updated Successfully!');
    }

    public function delete($id){
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect(route('categories.index'))->with('message_success', 'Deleted Successfully!');
    }


    public function uploadFile($file, $folder = 'uploads', $disk = 'public')
    {
        // Check if the file is valid
        if ($file->isValid()) {
            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $extension;
            $filePath = $file->storeAs($folder, $filename, $disk);
            return $filePath;  // Return the file path
        }
        return null;  // Return null if the file is not valid
    }

}
