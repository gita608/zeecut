<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\User;
use App\Models\Banners;
use App\Models\Product;
use App\Models\Product_images;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class OrderController extends ApiBaseController
{
    protected $order;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->order = new Order();
    }

    public function index(Request $request)
    {
         
        $cartIds = json_decode($request->cart_ids,true); // Expecting an array of cart IDs
 

        if (empty($cartIds)) {
            return $this->sendErrorResponse('No cart items selected.');
        }

        // dd($cartIds);

        // Generate order number and insert into `orders`
        $data['order_no'] = $this->order->generate_order_number();
        $data['cart_id'] = 0; // If you want to track general cart id, or leave null
        $data['user_id'] = $this->userId;
        $data['total_amount'] = 0;
        $data['created_at'] = date('Y-m-d H:i:s');

        $orderId = $this->order->add($data);

        // Now fetch cart items and insert into order_items
        $cartItems = DB::table('cart')
            ->whereIn('id', $cartIds)
            ->where('user_id', $this->userId)
            ->get();

        $total_amount = 0;

        foreach ($cartItems as $item) {

            $total_amount += $item->amount;

            DB::table('order_items')->insert([
                'order_id' => $orderId,
                'cart_id' => $item->id,
                'product_id' => $item->product_id,
                'collection_id' => $item->collection_id,
                'quantity' => $item->quantity,
                'price' => $item->amount, // or final amount logic
                'created_at' => now(),
            ]);

            // Optional: Mark cart as purchased
            DB::table('cart')->where('id', $item->id)->update(['purchase_status' => 1]);
        }

        DB::table('orders')->where('id', $orderId)->update(['total_amount' => $total_amount]);

        return $this->sendSuccessResponse([], 'Order placed successfully');
    }
 
    public function get_order_list(Request $request)
    {


        $datas = Order::where(['user_id' => $this->userId, 'status' => 'pending'])->get();

         foreach($datas as $key => $data){

            $items = OrderItem::where('order_id', $data->id)
            ->join('products', 'order_items.product_id', '=', 'products.id')  // Join products table
            ->select('order_items.*', 'products.name as product_name', 'products.price as product_price')  // Select necessary columns
            ->get();

        
            $datas[$key]->order_items = $items;

        }

        return $this->sendSuccessResponse($datas, 'success');

    }
 
    public function order_details(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendErrorResponse($validator->errors()->first(), 403);
        }

        return $this->sendSuccessResponse([], 'success');

    }

 }