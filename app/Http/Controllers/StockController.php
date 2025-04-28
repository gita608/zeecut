<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{
    public function index()
    {

        $data['list_items'] = Product::with(relations: 'stock')->get();
        $data['page_title'] = 'Stocks';
        $data['page_name'] = 'admin.stock.index';
        return view('admin.main', $data);

    }

    public function update_quantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer'
        ]);

        $action = $request->action;
        $product_id = $request->product_id;


        $quantity = Stock::where('product_id', $product_id)->first()->quantity ?? null;
        if (!empty($quantity)) {

            $data = [
                'product_id' => $product_id,
                'quantity' => $action == 'increase' ? $quantity + 1 : $quantity - 1,
                'created_at' => now()
            ];

            $stock = Stock::where('product_id', $product_id)->first();
            $stock->update($data);

            return response()->json([
                'success' => true,
                'quantity' => $data['quantity'],
            ]);

        } else {

            $data = [
                'product_id' => $product_id,
                'quantity' => $action == 'increase' ? 1 : 0,
                'created_at' => now()
            ];
            
            Stock::create($data);

            return response()->json([
                'success' => true,
                'quantity' => $data['quantity'],
            ]);

        }
        

    }
}
