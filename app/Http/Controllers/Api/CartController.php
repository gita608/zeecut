<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\ProductCollection;
use Illuminate\Support\Facades\DB;

class CartController extends ApiBaseController
{
    protected $cart;
    protected $product;
    protected $user;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->cart = new Cart();
        $this->product = new Product();
        $this->user = new User();
    }

    public function index(Request $request)
    {
        $joins = [
            ['products', 'cart.product_id', 'products.id', 'leftJoin'],
            ['product_collections', 'cart.collection_id', 'product_collections.id', 'leftJoin']
        ];

        $where = [
            ['cart.user_id', '=', $this->userId],
            ['cart.purchase_status', '=', 0],
            ['products.status', '=', 1],
        ];

        $select = [
            'cart.*',
            'products.name as product_name',
            'products.price as product_price',
            'products.discount_price as discount_price',
            'products.thumbnail as product_thumbnail',
            'products.minimum_limit as enter_quantity_limit',
            'products.sale_price',
            'products.unit',
            DB::raw("IF(cart.collection_id = 0, '', product_collections.title) as collection_name")
        ];

        $cart = $this->cart->getJoin($joins, $where, $select);
        $total_amount = 0;
        $total_discount_amount = 0;

        foreach ($cart as &$val) {
            $total_amount += $val->product_price;
            $total_discount_amount += $val->discount_price;
            $val['product_thumbnail'] = $val['product_thumbnail'] ? asset('storage/' . $val['product_thumbnail']) : '';
            $val['is_out_of_stock'] = 0;
            $val['unit'] = ($val->unit == 1) ? 'Kg' : (($val->unit == 2) ? 'L' : 'Qty');

        }

        $discounted_amount  = $total_amount - $total_discount_amount;
        $delivery_charge    = get_setting('delivery_charge');
        $total_payable      = $total_amount - $total_discount_amount + $delivery_charge;

        $data = [
            'cart_items' => $cart,
            'total_amount' => $total_amount,
            'total_discount' => $total_discount_amount,
            'discounted_amount' => $discounted_amount,
            'delivery_charge' => $delivery_charge,
            'total_payable' => $total_payable,
            'product_count' => Cart::where(['user_id' => $this->userId, 'purchase_status' => 0])->count(),
        ];

        return $this->sendSuccessResponse($data, 'Success');
    }

    public function add_cart(Request $request)
    {
        $validationResponse = $this->validateRequest($request, [
            'product_id' => 'required|integer',
            'collection_id' => 'nullable|integer',
            'quantity' => 'required|numeric',
        ]);

        if ($validationResponse) {
            return $validationResponse;
        }

        // Build where condition
        $where = ['product_id' => $request->product_id, 'user_id' => $this->userId];
        if ($request->collection_id > 0) {
            $where['collection_id'] = $request->collection_id;
        }

        $already_exist      = $this->cart->getData($where);
        $product_details    = $this->product->getData(['id' => $request->product_id], ['price', 'discount_price'])->first();

        if (!$already_exist->isEmpty()) {
            $existing = $already_exist->first();
            $quantity = $existing->quantity + $request->quantity;
            $updateData = [
                'quantity' => $quantity,
            ];
            $this->cart->update_record(['id' => $existing->id], $updateData);
        } else {
            $insertData = [
                'product_id' => $request->product_id,
                'collection_id' => $request->collection_id,
                'user_id' => $this->userId,
                'quantity' => $request->quantity,
            ];
            $this->cart->add($insertData);
        }

        return $this->sendSuccessResponse([], 'Cart updated successfully!');
    }

    // public function remove_cart(Request $request)
    // {
    //     $validationResponse = $this->validateRequest($request, [
    //         'cart_id' => 'required|integer',
    //         'quantity' => 'required|numeric',
    //     ]);

    //     if ($validationResponse) {
    //         return $validationResponse;
    //     }

    //     // Get the cart item by ID and ensure it belongs to the logged-in user
    //     $cart_item = $this->cart->getData([
    //         'id' => $request->cart_id,
    //         'user_id' => $this->userId
    //     ])->first();

    //     if (!$cart_item) {
    //         return $this->sendSuccessResponse([], 'No cart item found, nothing to remove.');
    //     }

    //     $current_quantity = $cart_item->quantity;
    //     $remove_quantity = $request->quantity;

    //     if ($remove_quantity >= $current_quantity) {
    //         // Remove the cart item entirely
    //         $this->cart->delete_record(['id' => $cart_item->id]);
    //     } else {
    //         // Fetch product price details
    //         $product_details = $this->product->getData(['id' => $cart_item->product_id], ['price', 'discount_price'])->first();
    //         $price = $product_details->price;
    //         $discount_price = $product_details->discount_price;

    //         // Calculate new quantity and amounts
    //         $updated_quantity = $current_quantity - $remove_quantity;
    //         $amount = $price * $updated_quantity;
    //         $discount_amount = $discount_price * $updated_quantity;

    //         $updateData = [
    //             'quantity' => $updated_quantity,
    //             'amount' => $amount,
    //             'discount_amount' => $discount_amount,
    //         ];

    //         $this->cart->update_record(['id' => $cart_item->id], $updateData);
    //     }

    //     return $this->sendSuccessResponse([], 'Cart updated successfully!');
    // }

    public function remove_cart(Request $request)
    {
        $validationResponse = $this->validateRequest($request, [
            'product_id' => 'required|integer',
            'collection_id' => 'required|integer',
            'quantity' => 'sometimes|numeric', // now optional
        ]);

        if ($validationResponse) {
            return $validationResponse;
        }

        $cart_item = Cart::where([
            'user_id' => $this->userId,
            'product_id' => $request->product_id,
            'collection_id' => $request->collection_id
        ])->first();

        if (!$cart_item) {
            return $this->sendSuccessResponse([], 'No Item Found!');
        }

        // If no quantity is provided, remove completely
        if (!$request->has('quantity') || $request->quantity <= 0) {
            $cart_item->delete();
            return $this->sendSuccessResponse([], 'Item removed from cart.');
        }

        $remove_quantity = $request->quantity;
        $current_quantity = $cart_item->quantity;
        $updated_quantity = $current_quantity - $remove_quantity;

        if ($updated_quantity <= 0) {
            $cart_item->delete();
            return $this->sendSuccessResponse([], 'Item removed from cart.');
        }

        $updateData = [
            'quantity' => $updated_quantity,
        ];

        $this->cart->update_record(['id' => $cart_item->id], $updateData);

        // Re-fetch updated cart item to return correct info
        $updated_cart_item = Cart::find($cart_item->id);

        return $this->sendSuccessResponse($updated_cart_item, 'Cart updated successfully!');
    }

    
    public function checkout(Request $request){
        $user = User::where('id', $this->userId)->first();

        // $cartIds = json_decode($request->cart_ids, true);
        // if (empty($cartIds)) {
        //     return $this->sendSuccessResponse([], 'Cart Id Required');
        // }


        $cart_data = Cart::where('user_id',$this->userId)->where('purchase_status', 0)->get();

        $total_amount = 0;
        $total_discount_amount = 0;

        foreach ($cart_data as $key => $item) {
            $total_amount += $item->amount;
            $total_discount_amount += $item->discount_amount;
            $cart_data[$key]->product = Product::where('id',$item['product_id'])->first()->name;
            $cart_data[$key]->collection = ProductCollection::where('id',$item['collection_id'])->first()->title;

            $items = OrderItem::where('order_id', $item->id)
                ->join('products', 'order_items.product_id', '=', 'products.id')  // Join products table
                ->select('order_items.*', 'products.name as product_name', 'products.price as product_price')  // Select necessary columns
                ->get();
            $cart_data[$key]->order_items = $items;
        }

        $user_data = $user->userdata();
        $delivery_charge = 0;
        $total_payable = $total_amount - $total_discount_amount + $delivery_charge;
        $summary = [
            'total_amount' => $total_amount,
            'delivery_charge' => $delivery_charge ,
            'discount_amount' => $total_discount_amount,
            'total_payable' => $total_payable
        ];
        $data = [
            'user_address' => $user_data['address']. ', '. $user_data['pincode'],
            'cart_data' => $cart_data,
            'summary' => $summary
        ];

        return $this->sendSuccessResponse($data, 'successfully!');

    }

}
