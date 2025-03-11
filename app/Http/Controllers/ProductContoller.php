<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
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


    public function index()
    {
        $data['list_items'] = $this->product->getJoin(
            joins: [
                ['categories', 'products.category_id', 'categories.id']
            ],
            where: [], // Add conditions if needed
            select: ['products.*', 'categories.name as category_name'], // Select required fields
            order_by: ['products.id' => 'DESC'] // Order by product ID in descending order
        );

        // dd($data['list_items']);
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
        // dd($_POST);

        $request->validate([
            'category' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation
        ]);

        $filePath = uploadFile($request->file('thumbnail'), 'product-images');
        // dd($filePath);


        // Insert data into database
        Product::create([
            'category_id' => $request->category,
            'name' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'thumbnail' => $filePath,
        ]);

        return redirect()->route('product.index')->with('message_success', 'Product added successfully!');
    }

    public function ajax_edit($id)
    {
        $data['edit_data'] = Product::findOrFail($id);
        $data['categories'] = Category::get(); // Fetch categories
        return view('admin.product.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|digits_between:10,15',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return redirect()->route('user.index')->with('message_success', 'User updated successfully!');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        if ($user->delete()) {
            return redirect()->route('user.index')->with('message_success', 'User deleted successfully!');
        } else {
            return redirect()->route('user.index')->with('message_danger', 'Failed to delete user.');
        }
    }






}
