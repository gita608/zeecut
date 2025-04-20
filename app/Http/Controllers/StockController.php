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
        log::info($request);
    }
}
