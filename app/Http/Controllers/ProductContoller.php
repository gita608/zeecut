<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductCollection;
use App\Models\Product_images;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductContoller extends Controller
{
    protected $product;

    public function __construct()
    {
        $this->product = new Product();
    }


    public function index(Request $request)
    {
        $where = [];

        if ($request->category_id) {
            $where[] = ['products.category_id', '=', $request->category_id];
        }

        $list_items = $this->product->getJoin(
            joins: [
                ['categories', 'products.category_id', 'categories.id'],
            ],
            where: $where,
            select: ['products.*', 'categories.name as category_name'],
            order_by: ['products.id' => 'DESC']
        );

        foreach ($list_items as $key => $list_item) {
            $list_items[$key]['collection_items'] = ProductCollection::where(['product_id' => $list_item['id']])->get();
            $list_items[$key]['has_collection'] = Category::where(['id' => $list_item['category_id']])->first()->has_collection;
        }


        $data['list_items'] = $list_items;

        // Log::info('Product List Data', ['list_items' => $data['list_items']]);
        $data['categories'] = Category::all();
        $data['page_title'] = 'Product';
        $data['page_name'] = 'admin.product.index';

        return view('admin.main', $data);
    }


    public function ajax_add()
    {
        $categories = Category::get();
        return view('admin.product.add', compact('categories')); // Pass to view
    }

    public function submit(Request $request)
    {
        $request->validate([
            'category' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'collection_title.*' => 'nullable|string',
            'collection_price.*' => 'nullable|numeric|min:0',
            'collection_sale_price.*' => 'nullable|numeric|min:0',
            'extra_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload thumbnail
        $filePath = uploadFile($request->file('thumbnail'), 'product-images');

        // Store Product
        $product = Product::create([
            'category_id'       => $request->category,
            'name'              => $request->title,
            'description'       => $request->description,
            'price'             => $request->price,
            'discount_price'    => $request->discount_price,
            'sale_price'        =>  $request->discount_price,
            'thumbnail'         => $filePath,
            'no_of_collection'  => $request->no_of_collection,
            'unit'              => $request->unit,
        ]);

        // Store Product Collections
        if ($request->has('collection_title')) {
            foreach ($request->collection_title as $index => $title) {
                if ($title) {
                    ProductCollection::create([
                        'product_id'    => $product->id,
                        'title'         => $title,
                        'price'         => $request->collection_price[$index] ?? 0,
                        'sale_price'    => $request->collection_sale_price[$index] ?? 0,
                    ]);
                }
            }
        }

        // Store Additional Product Images
        if ($request->hasFile('extra_images')) {
            foreach ($request->file('extra_images') as $image) {
                if ($image->isValid()) {
                    $imagePath = uploadFile($image, 'product-images');
                    Product_images::create([
                        'product_id' => $product->id,
                        'image' => $imagePath,
                    ]);
                }
            }
        }

        return redirect()->route('product.index')->with('message_success', 'Product and collections added successfully!');
    }


    public function ajax_edit($id)
    {
        $data['collections'] = ProductCollection::where(['product_id' => $id])->get();
        $data['edit_data'] = Product::findOrFail($id);
        $data['categories'] = Category::get(); // Fetch categories
        return view('admin.product.edit', $data);
    }

    public function view_images($id)
    {
        $data['edit_data'] = Product_images::where('product_id', $id)->get();
        Log::info('Product List Data', $data);
        return view('admin.product.view_images', $data);
    }

    public function update(Request $request, $id)
    {
        $data = [
            'category_id' => $request->category,
            'name' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'sale_price' => $request->discount_price,
            'no_of_collection' => $request->no_of_collection,
            'unit' => $request->unit,
        ];

        // Handle Thumbnail Upload
        if ($request->hasFile('thumbnail')) {
            $filePath = uploadFile($request->file('thumbnail'), 'product-images');
            $data['thumbnail'] = $filePath;
        }

        // Update Product Data
        $product = Product::findOrFail($id);
        $product->update($data);

        // Handle Collection Data
        if ($request->has('collection_title') && $request->has('collection_price')) {
            // Get existing collections to track which ones to keep/update
            $existingCollections = $product->collections()->get();
            $updatedCollectionIds = [];
            
            foreach ($request->collection_title as $key => $title) {
                // If there's an existing collection at this index, update it
                if (isset($existingCollections[$key])) {
                    $existingCollections[$key]->update([
                        'title' => $title,
                        'price' => $request->collection_price[$key] ?? 0,
                        'sale_price' => $request->collection_sale_price[$key] ?? 0,
                    ]);
                    $updatedCollectionIds[] = $existingCollections[$key]->id;
                } else {
                    // Create a new collection if no existing one at this index
                    $newCollection = $product->collections()->create([
                        'title' => $title,
                        'price' => $request->collection_price[$key] ?? 0,
                        'sale_price' => $request->collection_sale_price[$key] ?? 0,
                    ]);
                    $updatedCollectionIds[] = $newCollection->id;
                }
            }
            
            // Delete any collections that weren't updated (if there are more old ones than new ones)
            if (count($existingCollections) > count($updatedCollectionIds)) {
                $product->collections()
                    ->whereNotIn('id', $updatedCollectionIds)
                    ->delete();
            }
        } else {
            // If no collections were submitted, remove all existing collections
            $product->collections()->delete();
        }

        return redirect()->route('product.index')->with('message_success', 'Product updated successfully!');
    }



    public function delete($id)
    {
        $user = Product::findOrFail($id);

        if ($user->delete()) {
            return redirect()->route('product.index')->with('message_success', 'Product deleted successfully!');
        } else {
            return redirect()->route('product.index')->with('message_danger', 'Failed to delete user.');
        }
    }


    public function toggleStatus(Request $request)
    {
        // Log::error('post_data: ',$_POST);

        $product = Product::find($request->product_id);
        if ($product) {
            $product->status = $product->status == 1 ? 0 : 1; // Toggle status
            $product->save();
            return response()->json(['message' => 'Status updated successfully!']);
        }
        return response()->json(['message' => 'User not found!'], 404);
    }

    public function get_has_collection(Request $request)
    {

        $category = Category::find($request->category_id);
        // Log::info($category); // Check what data is coming

        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Category not found'], 404);
        }

        return response()->json(['status' => 'success', 'category' => $category]);
    }

    public function delete_image($id)
    {
        $image = Product_images::find(id: $id);

        if (!$image) {
            return response()->json(['success' => false, 'message' => 'Image not found.'], 404);
        }

        // Delete image from storage
        if ($image->image && Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        // Delete image record from database
        $image->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully.']);
    }
    public function upload_image(Request $request)
{
    Log::info('Upload Request: ' . json_encode($request->all()));

    if ($request->hasFile('images')) {
        $uploadedHTML = '';

        foreach ($request->file('images') as $file) {
            $filename = time() . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $storedPath = $file->storeAs('uploads/products', $filename, 'public');

            // Save to DB
            $image = new Product_images();
            $image->product_id = $request->product_id;
            $image->image = $storedPath;
            $image->save();

            // Now use real DB ID
            $uploadedHTML .= '
                <div class="col-md-3 mb-3" id="img-' . $image->id . '">
                    <div class="card">
                        <img src="' . asset("storage/{$storedPath}") . '" height="120" class="card-img-top" alt="Image">
                        <div class="card-body p-2 text-center">
                            <button class="btn btn-sm btn-danger delete-image" data-id="' . $image->id . '">Delete</button>
                        </div>
                    </div>
                </div>
            ';
        }

        return response()->json([
            'success' => true,
            'html' => $uploadedHTML
        ]);
    }

    return response()->json(['success' => false, 'message' => 'No files uploaded.']);
}





}
