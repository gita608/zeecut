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
            'icon' => 'nullable|image|max:2048', // max in KB (2048 = 2MB)
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
            'icon' => 'nullable|image|max:2048', // max in KB (2048 = 2MB)
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

    public function uploadFile($file, $baseFolder = 'uploads', $disk = 'public')
    {
        if ($file->isValid()) {
            $extension = strtolower($file->getClientOriginalExtension());
            $filename = uniqid() . '.' . $extension;
            $dateFolder = date('Y') . '/' . date('m');
            $folder = $baseFolder . '/' . $dateFolder;
            $path = $folder . '/' . $filename;

            // Create directory if not exists
            $fullPath = storage_path("app/{$disk}/{$folder}");
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            // Supported image extensions
            $imageExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($extension, $imageExtensions)) {
                $sourcePath = $file->getRealPath();
                list($width, $height) = getimagesize($sourcePath);

                // Set new width (max 1200px), auto height (keep ratio)
                $newWidth = 1200;
                if ($width <= $newWidth) {
                    // No resize needed, just move original file
                    $file->move($fullPath, $filename);
                    return "{$folder}/{$filename}";
                }

                $newHeight = intval(($newWidth / $width) * $height);

                // Create image resource based on file type
                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                        $srcImage = imagecreatefromjpeg($sourcePath);
                        break;
                    case 'png':
                        $srcImage = imagecreatefrompng($sourcePath);
                        break;
                    case 'webp':
                        $srcImage = imagecreatefromwebp($sourcePath);
                        break;
                    default:
                        return null;
                }

                // Resize
                $dstImage = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                $savePath = "{$fullPath}/{$filename}";

                // Save compressed image
                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($dstImage, $savePath, 75); // 75% quality
                        break;
                    case 'png':
                        imagepng($dstImage, $savePath, 6); // Compression 0-9 (6 is balanced)
                        break;
                    case 'webp':
                        imagewebp($dstImage, $savePath, 75);
                        break;
                }

                // Free memory
                imagedestroy($srcImage);
                imagedestroy($dstImage);

                return "{$folder}/{$filename}";
            } else {
                // Non-image files
                $file->storeAs($folder, $filename, $disk);
                return "{$folder}/{$filename}";
            }
        }

        return null;
    }

}
