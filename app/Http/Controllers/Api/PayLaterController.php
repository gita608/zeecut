<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\User;
use App\Models\Banners;
use App\Models\Product;
use App\Models\Product_images;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;

class PayLaterController extends ApiBaseController
{
    protected $category;
    protected $user;
    protected $banners;
    protected $product;
    protected $product_images;
    protected $cart;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->category = new Categories();
        $this->user = new User();
        $this->banners = new Banners();
        $this->product = new Product();
        $this->product_images = new Product_images();
        $this->cart = new Cart();
    }

    public function index(Request $request)
    {
        $userCredit = user_credit($this->userId);
        $creditBalance = credit_balance($this->userId);

        $data = [
            'user_limit' => $userCredit,
            'balance_amount' => $creditBalance,
            'used_amount' => $userCredit - $creditBalance,
            'history' => []
        ];

        $histories = Payment::where('user_id', $this->userId)
            ->where('payment_method', 2)
            ->get();

        $orderIds   = $histories->pluck('order_id')->unique();
        $orders     = Order::whereIn('id', $orderIds)->get()->keyBy('id');
        $orderItems = OrderItem::whereIn('order_id', $orderIds)->get()->groupBy('order_id');

        foreach ($histories as $history) {
            $order = $orders->get($history->order_id);
            if (!$order)
                continue;

            $productsList = [];

            foreach ($orderItems->get($history->order_id, []) as $orderItem) {
                $productDetails = $this->product->get_product_details($orderItem->product_id, $orderItem->collection_id);
                $productsList[] = [$productDetails['product'] ?? null];
            }

            $data['history'][] = [
                'order_no' => $order->order_no,
                'products' => $productsList,
                'amount' => $history->pay_later_credit,
                'status' => $history->status,
                'pending_date' => date('d-M-Y', strtotime($history->created_at)),
                'completed_date' => $history->completed_date ? date('d-M-Y', strtotime($history->completed_date)) : ''
            ];
        }

        return $this->sendSuccessResponse($data, 'Success');
    }


}
