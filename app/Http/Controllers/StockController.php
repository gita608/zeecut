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
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'action' => 'required|in:add,subtract'
        ]);

        try {
            $stock = Stock::firstOrNew(['product_id' => $validated['product_id']]);
            
            // Initialize quantity if it doesn't exist
            if (!$stock->exists) {
                $stock->quantity = 0;
            }

            // Calculate new quantity
            $newQuantity = $validated['action'] === 'add'
                ? $stock->quantity + $validated['quantity']
                : $stock->quantity - $validated['quantity'];

            // Prevent negative stock
            $newQuantity = max(0, $newQuantity);

            // Update stock
            $stock->quantity = $newQuantity;
            $stock->save();

            return response()->json([
                'success' => true,
                'quantity' => $newQuantity,
                'message' => 'Stock updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock: ' . $e->getMessage()
            ], 500);
        }
    }
}
