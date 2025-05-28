<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductCollection;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class OrderController extends ApiBaseController
{
    protected $order;
    protected $cart;
    protected $product;
    protected $productcollection;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->order    = new Order();
        $this->cart     = new Cart();
        $this->product  = new Product();
        $this->productcollection = new ProductCollection();
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
        $data['is_pay_later']   = $request->is_pay_later;
        $data['pay_later_credit']   = $request->pay_later_credit;
        $data['address']        = $request->address;
        $data['phone']          = $request->phone;
        $data['ordered_date']   = date('Y-m-d H:i:s');
        $data['created_at']     = date('Y-m-d H:i:s');

        $orderId = $this->order->add($data);


        $cartItems = DB::table('cart')
            ->whereIn('id', $cartIds)
            ->where('user_id', $this->userId)
            ->get();


        $total_price    = 0;
        $final_amount   = 0;

        foreach ($cartItems as $item) {

            $product    = $this->product->get_product_details($item->product_id,$item->collection_id);
            if($item->collection_id == 0){
                $quantity   =   $item->quantity;
            }else{
                $quantity   = $this->productcollection->get_quantity_of_collection($item->collection_id,$item->amount);
            }

            $price          =   $item->collection_id == 0 ? $product['product_price'] : $product['collection_price'];
            $sale_price     =   $item->collection_id == 0 ? $product['product_sale_price'] : $product['collection_sale_price'];
            $total_price    +=  $price * $quantity;
            $final_amount   +=  $sale_price * $quantity;

            DB::table('order_items')->insert([
                'order_id'      => $orderId,
                'cart_id'       => $item->id,
                'product_id'    => $item->product_id,
                'collection_id' => $item->collection_id,
                'quantity'      => $quantity,
                'price'         => $price * $quantity,  
                'sale_price'    => $sale_price * $quantity,
                'created_at'    => now(),
            ]);

           

            // Optional: Mark cart as purchased
            DB::table('cart')->where('id', $item->id)->update(['purchase_status' => 1]);
        }

        $final_amount_total = $final_amount + $delivery_charge;
        $total_price_total = $total_price + $delivery_charge;

        DB::table('orders')->where('id', $orderId)->update(values: ['total_amount' => $final_amount_total,'price_amount' => $total_price_total,'total_discount' => $total_price_total - $final_amount_total,'status' => 'placed']);

        //payment table    

        $credit_balance = credit_balance($this->userId);
        if($credit_balance > $request->pay_later_credit && $request->is_pay_later == 1){
            $final_total    = $final_amount_total - $request->pay_later_credit ;
        }elseif($credit_balance > 0){
            $final_total    = $final_amount_total - $credit_balance;
        }else{
            $final_total    = $final_amount_total;
        }

         $payment['order_id']            = $orderId;
         $payment['user_id']             = $this->userId;
         $payment['pending_amount']      = $final_total;
         $payment['pay_later_credit']    = $credit_balance > $request->pay_later_credit ? $request->pay_later_credit : $credit_balance;
         $payment['payment_method']      = ($request->is_pay_later == 1 && $payment['pay_later_credit'] > 0) ? 2 : 1;
         $payment['paid']                = 0;
         Payment::create($payment);

        return $this->sendSuccessResponse([], 'Order placed successfully');
    }
 
    public function get_order_list(Request $request)
    {
        $datas = Order::where(['user_id' => $this->userId])
            ->orderBy('id', 'desc') // Sort orders in descending order
            ->get();

        foreach ($datas as $key => $data) {
            $items = DB::table('order_items')
                ->select(DB::raw("
                    IF(
                        product_collections.title IS NOT NULL,
                        CONCAT(products.name, '-', product_collections.title),
                        products.name
                    ) AS item_name
                "),
                DB::raw("ROUND(order_items.quantity, 2) AS quantity"))
                ->join('products', 'products.id', '=', 'order_items.product_id')
                ->leftJoin('product_collections', 'product_collections.id', '=', 'order_items.collection_id')
                ->where('order_items.order_id', $data['id'])
                ->orderBy('order_items.id', 'desc') // Sort items in descending order
                ->get();

            $datas[$key]->status = $data['status'];
            $datas[$key]->ordered_date = date('d-M-Y', strtotime($data['ordered_date']));
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

        $order_id = $request->order_id;

        $datas = [];
        $data = Order::where(['user_id' => $this->userId, 'id' => $order_id])->get()->first();
        $items = OrderItem::where('order_id', $data->id)
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
            DB::raw("ROUND(order_items.quantity, 2) AS quantity"), // rounding to 2 decimal places
            DB::raw("ROUND(order_items.price) AS price"), // rounding to 2 decimal places
            'order_items.sale_price',
            'products.unit',
            DB::raw("CONCAT('" . asset('storage') . "/', products.thumbnail) AS thumbnail")
        )
        ->get();
 
            $datas['total_amount']      = $data['price_amount'];
            $datas['total_payble']      = $data['total_amount'];
            $datas['total_discount']    = $data['total_discount'];
            $datas['address']           = $data['address'];
            $datas['phone']             = $data['phone'];
            $datas['ordered_date']      = date('d-M-Y',strtotime($data['ordered_date']));
            $datas['order_status']      = ucfirst($data['status']);
            $datas['delivery_charge']   = get_setting('delivery_charge');
            $datas['order_items']       = $items;
            $datas['status']            = $this->order->get_order_status($data);

        return $this->sendSuccessResponse($datas, 'success');

    }

 }