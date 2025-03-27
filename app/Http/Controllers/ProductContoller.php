<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductCollection;
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
        ]);

        $filePath = uploadFile($request->file('thumbnail'), 'product-images');

        // Store Product
        $product = Product::create([
            'category_id' => $request->category,
            'name' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'thumbnail' => $filePath,
            'no_of_collection' => $request->no_of_collection,

        ]);

        // Store Product Collections
        foreach ($request->collection_title as $index => $title) {
            ProductCollection::create([
                'product_id' => $product->id,
                'title' => $title,
                'price' => $request->collection_price[$index] ?? 0,
            ]);
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

    public function update(Request $request, $id)
    {

        // Log::info('Product List Data', $_POST);

        // $request->validate([
        //     'category' => 'required|exists:categories,id',
        //     'title' => 'required|string|max:255',
        //     'description' => 'required|string',
        //     'price' => 'required|numeric|min:0',
        //     'discount_price' => 'nullable|numeric|min:0',
        //     'no_of_collection' => 'numeric',
        //     'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        //     'collection_title.*' => 'required|string|max:255', // Validate collection title
        //     'collection_price.*' => 'required|numeric|min:0',  // Validate collection price
        // ]);

        // Log::info('Product List Data2', $_POST);

        $data = [
            'category_id' => $request->category,
            'name' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'no_of_collection' => $request->no_of_collection,
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
            $product->collections()->delete(); // Delete previous collection data

            foreach ($request->collection_title as $key => $title) {
                $product->collections()->create([
                    'title' => $title,
                    'price' => $request->collection_price[$key] ?? 0,
                ]);
            }
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


}
