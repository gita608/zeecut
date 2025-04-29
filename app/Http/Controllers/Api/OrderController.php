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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class OrderController extends ApiBaseController
{
    protected $order;
    protected $cart;
    protected $product;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->order = new Order();
        $this->cart = new Cart();
        $this->product = new Product();
    }

    public function index(Request $request)
    {
         
        $cartIds = json_decode($request->cart_ids,true); // Expecting an array of cart IDs
 

        if (empty($cartIds)) {
            return $this->sendErrorResponse('No cart items selected.');
        }

        $delivery_charge = get_setting('delivery_charge');

        // Generate order number and insert into `orders`
        $data['order_no'] = $this->order->generate_order_number();
        $data['user_id'] = $this->userId;
        $data['total_amount'] = 0;
        $data['address'] = $request->address;
        $data['phone'] = $request->phone;
        $data['created_at'] = date('Y-m-d H:i:s');

        $orderId = $this->order->add($data);

        // Now fetch cart items and insert into order_items
        $cartItems = DB::table('cart')
            ->whereIn('id', $cartIds)
            ->where('user_id', $this->userId)
            ->get();

        $final_amount = 0;    

        foreach ($cartItems as $item) {

            $product    = $this->product->get_product_details($item->product_id,$item->collection_id);

            $price          =   $item->collection_id == 0 ? $product['product_price'] : $product['collection_price'];
            $sale_price     =   $item->collection_id == 0 ? $product['product_sale_price'] : $product['collection_sale_price'];
            $final_amount   +=  $sale_price * $item->quantity;

            DB::table('order_items')->insert([
                'order_id'      => $orderId,
                'cart_id'       => $item->id,
                'product_id'    => $item->product_id,
                'collection_id' => $item->collection_id,
                'quantity'      => $item->quantity,
                'price'         => $price * $item->quantity,  
                'sale_price'    => $sale_price * $item->quantity,
                'created_at'    => now(),
            ]);

            // Optional: Mark cart as purchased
            DB::table('cart')->where('id', $item->id)->update(['purchase_status' => 1]);
        }

        DB::table('orders')->where('id', $orderId)->update(values: ['total_amount' => $final_amount]);

        return $this->sendSuccessResponse([], 'Order placed successfully');
    }
 
    public function get_order_list(Request $request)
    {


        $datas = Order::where(['user_id' => $this->userId, 'status' => 'pending'])->get();

         foreach($datas as $key => $data){

            $items = OrderItem::where('order_id', $data->id)
            ->join('products', 'order_items.product_id', '=', 'products.id')  // Join products table
            ->join('product_collections', 'product_collections.id', '=', 'order_items.collection_id')  // Join products table
            ->select('order_items.id','order_items.quantity', 'products.name as product', 'product_collections.title as collection')  // Select necessary columns
            ->get();

            $datas[$key]->status        = $data['status'] == 'pending' ? 'Placed' : $data['status'];
            $datas[$key]->created_at    = date('d-m-Y',strtotime($data['created_at']));
            $datas[$key]->order_items   = $items;

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

        $order_id = $request->order_id;

        $datas = Order::where(['user_id' => $this->userId, 'status' => 'pending', 'id' => $order_id])->get();
 
        foreach($datas as $key => $data){

            $items = OrderItem::where('order_id', $data->id)
            ->join('products', 'order_items.product_id', '=', 'products.id')  // Join products table
            ->join('product_collections', 'product_collections.id', '=', 'order_items.collection_id','left')  // Join products table
            ->select('order_items.*', 'products.name as product', 'product_collections.title as collection')  // Select necessary columns
            ->get();

            $datas[$key]->created_at    = date('d-m-Y',strtotime($data['created_at']));
            $datas[$key]->status        = $data['status'] == 'pending' ? 'Placed' : $data['status'];
            $datas[$key]->order_items   = $items;

        }

        return $this->sendSuccessResponse($datas, 'success');

    }

 }