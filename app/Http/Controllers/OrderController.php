<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    protected $order;
    protected $order_item;

    public function __construct()
    {
        $this->order = new Order();
        $this->order_item = new OrderItem();
    }

    /**
     * Display a listing of the categories.
     */


    public function index()
    {
        $data['list_items'] = $this->order->getData();

        foreach ($data['list_items'] as $key => $order) {
            $items = OrderItem::where('order_id', $order->id)
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->select('order_items.*', 'products.name as product_name', 'products.price as product_price', 'products.unit')
                ->get();

            $data['list_items'][$key]->order_items = $items;
        }

        $data['page_title'] = 'Order';
        $data['page_name']  = 'admin.order.index';

        return view('admin.main', $data);
    }

    public function updateStatus($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        $order->status = request('status');
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function details($order_id)
    {
        // Define join details for fetching order with user info
        $joins = [
            ['users', 'users.id', 'orders.user_id', 'leftJoin'],
        ];

        // Define which fields to select
        $select = [
            'orders.*',
            'users.name as user_name',
            'users.email as user_email',
            'users.phone as user_phone',
        ];

        $where = [
            ['orders.id', '=', $order_id],
        ];

        // Fetch the single order with user info
        $order = $this->order->getJoin($joins, $where, $select)->first(); // Use `first()` to get a single object

        if (!$order) {
            return redirect()->back()->with('message_danger', 'Order not found.');
        }

        // Get the order items with product details
        $order_items = OrderItem::where('order_id', $order_id)
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('order_items.*', 'products.name as product_name', 'products.price as product_price', 'products.unit')
            ->get();

        // Attach items to order object
        $order->order_items = $order_items;

        // Send to view
        $data['order'] = $order;
        $data['page_title'] = 'Order Details';
        $data['page_name'] = 'admin.order.details';

        return view('admin.main', $data);
    }

}
