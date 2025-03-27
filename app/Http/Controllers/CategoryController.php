<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;

class CategoryController extends Controller
{
    protected $category;

    public function __construct()
    {
        $this->category = new Categories();
    }

    /**
     * Display a listing of the categories.
     */
   

    public function index()
    {
        $data['list_items'] = $this->category->getData();
        $data['page_title'] = 'Category';
        $data['page_name']  = 'admin.category.index';

        return view('admin.main', $data);
    }

    public function ajax_add()
    {
        return view('admin.category.add');
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional validation
        ]);
    
        // Check if file is uploaded before calling uploadFile()
        $filePath = $request->hasFile('icon') ? uploadFile($request->file('icon'), 'category-images') : null;
    
        $data = [
            'name' => $request->name,
            'icon' => $filePath, // Store as NULL if no file is uploaded
            'has_collection' => $request->has_collection ?? 0,
            'description' => $request->description,
            'created_at' => now() // Laravel's helper for current timestamp
        ];
    
        $this->category->add($data);
    
        return redirect()->route('category.index')->with('message_success', 'Category added successfully!');
    }

    /**
     * Display the specified category.
     */
    public function ajax_edit($id)
    {
        $data['edit_data'] = $this->category->getData(['id' => $id])->first();
        return view('admin.category.edit', $data);
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $category = $this->category->getData(['id' => $id])->first();

        if (!$category) {
            return redirect()->route('category.index')->with('error', 'Category not found.');
        }

        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if(!empty($request->icon)){
            $filePath = uploadFile($request->file('icon'), 'category-images');
        }

        // dd($request);

        $data = [
            'name' => $request->name,
            'icon' => $filePath ?? '',
            'has_collection' => $request->has_collection ?? 0,
            'description' => $request->description,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $updated = $this->category->update_record(['id' => $id], $data);

        if ($updated) {
            return redirect()->route('category.index')->with('success', 'Category updated successfully!');
        }

        return redirect()->route('category.index')->with('error', 'Failed to update category.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function delete($id)
    {
        $deleted = $this->category->delete_record(['id' => $id]);

        if ($deleted) {
            return redirect()->route('category.index')->with('success', 'Category deleted successfully!');
        }

        return redirect()->route('category.index')->with('error', 'Failed to delete category.');
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
