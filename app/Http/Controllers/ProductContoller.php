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
    protected $userModel;

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

        $data['list_items'] = $this->product->getJoin(
            joins: [
                ['categories', 'products.category_id', 'categories.id']
            ],
            where: $where,
            select: ['products.*', 'categories.name as category_name'],
            order_by: ['products.id' => 'DESC']
        );

        $data['categories'] = Category::all();
        $data['page_title'] = 'Product';
        $data['page_name'] = 'admin.product.index';

        return view('admin.main', $data);
    }


    public function ajax_add()
    {
        $categories = Category::get(); // Fetch categories
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
        $data['edit_data'] = Product::findOrFail($id);
        $data['categories'] = Category::get(); // Fetch categories
        return view('admin.product.edit', $data);
    }

    public function update(Request $request, $id)
    {
        Log::error($_POST);

        $request->validate([
            'category' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Add this validation for file
        ]);

        $data = [
            'category_id' => $request->category,
            'name' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
        ];

        if ($request->hasFile('thumbnail')) {
            $filePath = uploadFile($request->file('thumbnail'), 'product-images');
            $data['thumbnail'] = $filePath;
        } else {
            Log::error('No file uploaded in request');
        }

        $product = Product::findOrFail($id);
        $product->update($data);

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




}
