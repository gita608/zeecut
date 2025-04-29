<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Models\Stock;
use App\Models\OrderItem;
use App\Models\ProductCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends ApiBaseController
{
    protected $cart;
    protected $product;
    protected $user;
    protected $stock;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->cart = new Cart();
        $this->product = new Product();
        $this->user = new User();
        $this->stock = new Stock();
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
            'product_collections.sale_price as collection_sale_price',
            'product_collections.price as collection_price',
            DB::raw("IF(cart.collection_id = 0, '', product_collections.title) as collection_name")
        ];

        $cart = $this->cart->getJoin($joins, $where, $select);
        $total_amount = 0;
        $total_discount_amount = 0;

        foreach ($cart as &$val) {

            if ($val->collection_id > 0) {
                // $collection_price = ProductCollection::where(['id' => $val->collection_id])->first()->price;
                $val['product_name']    = $val->product_name.' - '.$val->collection_name;
                $val['product_price']   = $val->collection_price;
                $val['discount_price']  = $val->collection_sale_price;
                $val['sale_price']      = $val->collection_sale_price * $val->quantity;
            } else {
                $val['sale_price']      = $val->sale_price * $val->quantity;
            }

            $total_amount               += $val->product_price;
            $total_discount_amount      += $val->discount_price;
            $val['product_thumbnail']   = $val['product_thumbnail'] ? asset('storage/' . $val['product_thumbnail']) : '';
            $val['unit']                = ($val->unit == 1) ? 'Kg' : (($val->unit == 2) ? 'L' : 'Qty');
            $val['is_out_of_stock']     = $this->stock->get_product_stock($val->product_id) >= 1 ? 0 : 1;

        }

        $cart_data = $this->cart->get_user_cart_data($this->userId);
        $data = [
            'cart_items' => $cart,
            'min_order_amout' => get_setting('min_order_amout'),
            'total_amount' => $cart_data['total_amount'],
            'total_payable' => $cart_data['total_payable'],
            'total_discount' => $cart_data['total_discount'],
            'delivery_charge' => $cart_data['delivery_charge'],
            'product_count' => $cart_data['product_count'],
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
        $where = ['product_id' => $request->product_id, 'user_id' => $this->userId,'purchase_status' => 0];
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
            $message = 'Cart updated successfully!';
        } else {
            $insertData = [
                'product_id' => $request->product_id,
                'collection_id' => $request->collection_id,
                'user_id' => $this->userId,
                'quantity' => $request->quantity,
            ];
            $this->cart->add($insertData);
            $message = 'Cart Insert successfully!';
        }

        return $this->sendSuccessResponse([], );
    }
 
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


    public function checkout(Request $request)
    {
        $user       = User::where('id', $this->userId)->first();
        $user_data  = $user->userdata();


        $cart_data = Cart::where('user_id', $this->userId)->where('purchase_status', 0)->get();

        foreach ($cart_data as $key => $item) {

            $data = $this->product->get_product_details($item->product_id, $item->collection_id);

            if ($data['has_collection'] == 0) {
                $price      = $data['product_price'] * $item->quantity;
                $sale_price = $data['product_sale_price'] * $item->quantity;
            } else {
                $price      = $data['collection_price'] * $item->quantity;
                $sale_price = $data['collection_sale_price'] * $item->quantity;
            }

            $cart_data[$key]->product       = $data['collection'] != null ?  $data['product'].' - '.$data['collection'] : $data['product'];
            $cart_data[$key]->collection    = $data['collection'];
            $cart_data[$key]->unit          = $data['unit'];
            $cart_data[$key]->thumbnai      = $data['product_image'];
            $cart_data[$key]->price         = $price;
            $cart_data[$key]->sale_price    = $sale_price;
        }

        $cart_total_data = $this->cart->get_user_cart_data($this->userId);
        $summary = [
            'total_amount'      => $cart_total_data['total_amount'],
            'delivery_charge'   => $cart_total_data['delivery_charge'],
            'discount_amount'   => $cart_total_data['total_discount'],
            'total_payable'     => $cart_total_data['total_payable'] + $cart_total_data['delivery_charge'],
        ];

        $data = [
            'user_address'  => $user_data['address'] . ', ' . $user_data['pincode'],
            'phone'         => $user_data['phone'],
            'cart_data'     => $cart_data,
            'summary'       => $summary
        ];

        return $this->sendSuccessResponse($data, 'successfully!');

    }

}
