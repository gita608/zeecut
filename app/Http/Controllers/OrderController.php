<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Notifications;
use App\Models\User;

class OrderController extends Controller
{
    protected $order;
    protected $order_item;
    protected $notifications;
    protected $user;

    public function __construct()
    {
        $this->order = new Order();
        $this->order_item = new OrderItem();
        $this->notifications = new Notifications();
        $this->user = new User();
    }

    /**
     * Display a listing of the categories.
     */


    public function index(Request $request)
    {
        // Define join details for fetching orders with user info
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

        $where = [];

        // Apply status filter
        if ($request->filled('status')) {
            $where[] = ['orders.status', '=', $request->status];
        }

        // Debug: Log the request parameters
        // \Illuminate\Support\Facades\Log::info('Order Filter Debug', [
        //     'date_range' => $request->date_range,
        //     'date_from' => $request->date_from,
        //     'date_to' => $request->date_to,
        //     'status' => $request->status
        // ]);

        // Apply date range filter
        if ($request->filled('date_range') && $request->date_range !== 'custom') {
            // Handle predefined date ranges
            $today = now();
            $fromDate = null;
            $toDate = null;
            
            switch($request->date_range) {
                case 'today':
                    $fromDate = $today->copy()->startOfDay();
                    $toDate = $today->copy()->endOfDay();
                    break;
                case 'yesterday':
                    $yesterday = $today->copy()->subDay();
                    $fromDate = $yesterday->copy()->startOfDay();
                    $toDate = $yesterday->copy()->endOfDay();
                    break;
                case 'last_7_days':
                    $fromDate = $today->copy()->subDays(6)->startOfDay();
                    $toDate = $today->copy()->endOfDay();
                    break;
                case 'this_month':
                    $fromDate = $today->copy()->startOfMonth();
                    $toDate = $today->copy()->endOfDay();
                    break;
                case 'last_month':
                    $lastMonth = $today->copy()->subMonth();
                    $fromDate = $lastMonth->copy()->startOfMonth();
                    $toDate = $lastMonth->copy()->endOfMonth();
                    break;
                case 'two_months_ago':
                    $twoMonthsAgo = $today->copy()->subMonths(2);
                    $fromDate = $twoMonthsAgo->copy()->startOfMonth();
                    $toDate = $twoMonthsAgo->copy()->endOfMonth();
                    break;
            }
            
            if ($fromDate && $toDate) {
                $fromDateStr = $fromDate->format('Y-m-d H:i:s');
                $toDateStr = $toDate->format('Y-m-d H:i:s');
                
                \Illuminate\Support\Facades\Log::info('Date Range Calculated', [
                    'range' => $request->date_range,
                    'from' => $fromDateStr,
                    'to' => $toDateStr
                ]);
                
                $where[] = ['orders.created_at', '>=', $fromDateStr];
                $where[] = ['orders.created_at', '<=', $toDateStr];
            }
        } else {
            // Handle custom date range or individual date filters
            if ($request->filled('date_from')) {
                $where[] = ['orders.created_at', '>=', $request->date_from . ' 00:00:00'];
            }

            if ($request->filled('date_to')) {
                $where[] = ['orders.created_at', '<=', $request->date_to . ' 23:59:59'];
            }
        }

        // Debug: Log the where conditions
        \Illuminate\Support\Facades\Log::info('Where conditions', $where);
        
        // Get orders with user information, ordered by latest first
        $data['list_items'] = $this->order->getJoin($joins, $where, $select, ['orders.created_at' => 'DESC']);
        
        // Debug: Log the count of results
        \Illuminate\Support\Facades\Log::info('Orders found', ['count' => count($data['list_items'])]);

        // Get order items for each order
        foreach ($data['list_items'] as $key => $order) {
            $items = OrderItem::where('order_id', $order->id)
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->select('order_items.*', 'products.name as product_name', 'products.price as product_price', 'products.unit')
                ->get();

            $data['list_items'][$key]->order_items = $items;
        }

        // Pass filter values to view
        $data['filters'] = [
            'status' => $request->status,
            'date_range' => $request->date_range,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ];

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

        $status = request()->input('status');
        $allowedStatuses = ['placed', 'packed', 'dispatched', 'delivered'];

        if (!in_array($status, $allowedStatuses)) {
            return redirect()->back()->with('error', 'Invalid status selected.');
        }

        if ($status === $order->status) {
            return redirect()->back()->with('info', 'Order is already marked as ' . ucfirst($status) . '.');
        }

        $statusDates = [
            'placed'     => 'ordered_date',
            'packed'     => 'packed_date',
            'dispatched' => 'dispatched_date',
            'delivered'  => 'delivered_date',
        ];

        $now = now();

        // Get the indexes for comparison
        $oldIndex = array_search($order->status, $allowedStatuses);
        $newIndex = array_search($status, $allowedStatuses);

        // Update the new/current status date to now
        $order->{$statusDates[$status]} = $now;

        // If going backward, clear future status dates
        foreach ($allowedStatuses as $i => $s) {
            if ($i > $newIndex) {
                $order->{$statusDates[$s]} = null;
            }
        }

        // If going forward, set all previous status dates if not already set
        foreach ($allowedStatuses as $i => $s) {
            if ($i < $newIndex && empty($order->{$statusDates[$s]})) {
                $order->{$statusDates[$s]} = $now; // You can keep this as original created time if tracked separately
            }
        }

        $order->status = $status;
        $order->save();

        // Notify user
        $user = User::select('notification_token')->find($order->user_id);
        if (!$user || empty($user->notification_token)) {
            return redirect()->back()->with('warning', 'Order updated, but user notification token not found.');
        }

        $title = 'Order ' . ucfirst($status);
        $description = 'Your order #' . $order->order_no . ' has been ' . ucfirst($status);

        $this->send_notification(
            $user->notification_token,
            $order->id,
            'order',
            $title,
            $description
        );

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    private function send_notification($token, $orderid, $type, $title, $description = '', $image = '')
    {
        $data = [
            'notification_token'       => $token,
            'notification_title'       => $title,
            'notification_description' => $description,
            'notification_image'       => $image,
            'type'                     => $type,
            'order_id'                 => $orderid,
        ];

        return $this->notifications->send_test_notification($data);
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

    public function invoice($order_id)
    {
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

        $data = [
            'title' => 'Order Details',
            'order' => $order
        ];

        $pdf = Pdf::loadView('admin.order.invoice', $data);
        // return $pdf->download('dompdf-sample.pdf');
        return $pdf->stream('invoice.pdf');
    }
}
