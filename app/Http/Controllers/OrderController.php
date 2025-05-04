<?php

namespace App\Http\Controllers;

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
}
