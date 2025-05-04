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
        $data['order_no']       = $this->order->generate_order_number();
        $data['user_id']        = $this->userId;
        $data['total_amount']   = 0;
        $data['address']        = $request->address;
        $data['phone']          = $request->phone;
        $data['ordered_date']   = date('Y-m-d H:i:s');
        $data['created_at']     = date('Y-m-d H:i:s');

        $orderId = $this->order->add($data);

        // Now fetch cart items and insert into order_items
        $cartItems = DB::table('cart')
            ->whereIn('id', $cartIds)
            ->where('user_id', $this->userId)
            ->get();


        $total_price    = 0;
        $final_amount   = 0;

        foreach ($cartItems as $item) {

            $product    = $this->product->get_product_details($item->product_id,$item->collection_id);

            $price          =   $item->collection_id == 0 ? $product['product_price'] : $product['collection_price'];
            $sale_price     =   $item->collection_id == 0 ? $product['product_sale_price'] : $product['collection_sale_price'];
            $total_price    +=  $price * $item->quantity;
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

        $final_amount_total = $final_amount + $delivery_charge;
        $total_price_total = $total_price + $delivery_charge;

        DB::table('orders')->where('id', $orderId)->update(values: ['total_amount' => $final_amount_total,'price_amount' => $total_price_total,'total_discount' => $total_price_total - $final_amount_total,'status' => 'placed']);

        return $this->sendSuccessResponse([], 'Order placed successfully');
    }
 
    public function get_order_list(Request $request)
    {


        $datas = Order::where(['user_id' => $this->userId])->get();

         foreach($datas as $key => $data){

            $items = DB::table('order_items')
            ->select(DB::raw("
                IF(
                    product_collections.title IS NOT NULL,
                    CONCAT(products.name, '-', product_collections.title),
                    products.name
                ) AS item_name
            ",),'order_items.quantity')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('product_collections', 'product_collections.id', '=', 'order_items.collection_id')
            ->where('order_items.order_id', $data['id'])
            ->get();




            $datas[$key]->status        =   $data['status'];
            $datas[$key]->ordered_date  =   date('d-M-Y',strtotime($data['ordered_date']));            
            $datas[$key]->order_items   =   $items;

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

        $datas = [];
        $data = Order::where(['user_id' => $this->userId, 'id' => $order_id])->get()->first();
        $items = OrderItem::where('order_id', $data->id)
        ->join('orders', 'orders.id', '=', 'order_items.order_id')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('product_collections', 'product_collections.id', '=', 'order_items.collection_id', 'left')
        ->select(
            DB::raw("
                IF(
                    product_collections.title IS NOT NULL,
                    CONCAT(products.name, '-', product_collections.title),
                    products.name
                ) AS item_name
            "),
            DB::raw("
                CASE products.unit
                    WHEN 1 THEN 'Kg'
                    WHEN 2 THEN 'L'
                    WHEN 3 THEN 'Qty'
                    ELSE 'unknown'
                END AS unit_name
            "),
            'order_items.quantity',
            'order_items.price',
            'order_items.sale_price',
            'products.unit',
            'orders.status',
            DB::raw("CONCAT('" . asset('storage') . "/', products.thumbnail) AS thumbnail")
        )
        ->get();

 
            $datas['total_amount']      = $data['price_amount'];
            $datas['total_payble']      = $data['total_amount'];
            $datas['total_discount']    = $data['total_discount'];
            $datas['address']       = $data['address'];
            $datas['phone']         = $data['phone'];
            $datas['ordered_date']  = date('d-M-Y',strtotime($data['ordered_date']));
            $datas['delivery_charge']= get_setting('delivery_charge');
            $datas['order_items']   = $items;
            $datas['status']        = $this->order->get_order_status($data);

        return $this->sendSuccessResponse($datas, 'success');

    }

 }