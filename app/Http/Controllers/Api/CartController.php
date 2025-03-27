<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class CartController extends ApiBaseController
{
    protected $cart;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->cart = new Cart();
        
    }
    
    public function index(Request $request)
    {
        $joins = [
            ['products', 'cart.product_id', 'products.id', 'leftJoin'],
            ['product_collections', 'cart.collection_id', 'product_collections.id', 'leftJoin']
        ];

        $where = [
            ['cart.user_id', '=', $this->userId],
            ['products.status', '=', 1],
        ];

        $select = [
            'cart.*',
            'products.name as product_name', 
            'products.price as product_price',
            'products.discount_price as discount_price',
            'products.thumbnail as product_thumbnail',
            DB::raw("IF(cart.collection_id = 0, '', product_collections.title) as collection_name")
        ];

        $cart = $this->cart->getJoin($joins, $where, $select);

        foreach($cart as &$val){
            $val['product_thumbnail'] = $val['product_thumbnail'] ? asset($val['product_thumbnail']) : '';
        }

        $data = [
            'cart_items' => $cart,
            'total_amount' => 200,
            'total_discount' => 50,
            'discounted_amount' => 150,
            'delivery_charge' => 50,
            'total_payable' => 200,
        ];

        return $this->sendSuccessResponse($data, 'Success');
    }

}