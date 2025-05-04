<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $order;

    public function __construct(){
        $this->order = new Order();
    }
    
    public function index()
    {
        $orders_with_users = $this->order->getJoin(
            [
                ['users', 'orders.user_id', 'users.id', 'leftJoin']
            ],
            [],
            ['orders.*', 'users.name as customer_name'],
            ['orders.id' => 'desc'],
            5
        );
        
        $data = [
            'customer_count' => User::where('role_id', 2)->count(),
            'new_customers' => User::where('role_id', 2)
                                ->where('created_at', '>=', now()->subDays(7))
                                ->count(),
            'order_count' => Order::count(),
            'order_pending_count' => Order::where('status','pending')->count(),
            'order_delivered_count' => Order::where('status','delivered')->count(),
            'order_dispatched_count' => Order::where('status','dispatched')->count(),
            'order_packed_count' => Order::where('status','packed')->count(),
            'payment_completed_count' => Payment::where('status', 'completed')->count(),
            'revenue' => Payment::where('status', 'completed')->sum('amount'),
            'stock_count' => Stock::sum('quantity'),
            'low_stock_items' => Stock::where('quantity', '<', 10)->count(),
            'product_count' => Product::count(),
            'latest_orders' => $orders_with_users,
            'page_title' => 'Dashboard',
            'page_name' => 'admin.dashboard.index'
        ];
        
        return view('admin.main', $data); 
    }
}