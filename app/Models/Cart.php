<?php

namespace App\Models;

use App\Models\Stock;
use Illuminate\Support\Facades\DB;


class Cart extends BaseModel
{
    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'product_id',
        'collection_id',
        'quantity',
        'amount',
        'discount_amount',
        'amount'
    ];

    public function __construct()
    {
        parent::__construct();
    }
    


    public function get_user_cart_data($user_id,$cart_id=null){

        $this->stock = new Stock();


        $where = [];
        $where['user_id']           = $user_id;
        $where['purchase_status']   = 0;

        if($cart_id){
            $where['id']   = $cart_id;
        }


        $carts = Cart::where($where)
        ->join('product_collections', 'cart.collection_id', '=', 'product_collections.id','left')
        ->select('cart.*') 
        ->get();            


        $total_amount = 0;
        $total_discount = 0;
        $actual_total_amount = 0;
        $discount = 0;
        foreach($carts as $cart){

            $collection = ProductCollection::where(['id' => $cart->collection_id])->first();
            $product    = Product::where(['id' => $cart->product_id])->first();
            $is_stock   = $this->stock->get_product_stock($cart->product_id) > 0 ? 1 : 0;

            if($is_stock == 1){
                if($cart->collection_id > 0){


                    $quantity = $collection->sale_price > 0 ? $cart->amount/$collection->sale_price : 0;
                    
                    $discount = $collection->price - $collection->sale_price;

                    $actual_total_amount    += ($collection->price * $quantity);
                    $total_amount           += $collection->sale_price * $quantity;
                    $total_discount         += $discount * $quantity;

                }else{

                    $discount = $product->price - $product->discount_price;

                    $actual_total_amount    += ($product->price * $cart->quantity);
                    $total_amount           += ($product->discount_price * $cart->quantity);
                    $total_discount         += $discount * $cart->quantity;

                }
            } 
        }

        $data = [
            'total_amount'      => round($actual_total_amount),
            'total_payable'     => round($total_amount),
            'total_discount'    => round($total_discount),
            'delivery_charge'   => get_setting('delivery_charge'),
            'product_count'     => $carts->count(),
        ];

        return $data;
    }

    // public function get_user_cart_data($user_id,$cart_id=null){

    //     $this->stock = new Stock();

    //     $where = [];
    //     $where['user_id']           = $user_id;
    //     $where['purchase_status']   = 0;

    //     if($cart_id){
    //         $where['id']   = $cart_id;
    //     }

    //     $carts = Cart::where($where)
    //     ->join('product_collections', 'cart.collection_id', '=', 'product_collections.id')
    //     ->select('cart.*') // adjust fields as needed
    //     ->get();
    
    //     $total_amount = 0;
    //     $total_discount = 0;
    //     $actual_total_amount = 0;
    //     $discount = 0;
    //     foreach($carts as $cart){

    //         $collection = ProductCollection::where(['id' => $cart->collection_id])->first();
    //         $product    = Product::where(['id' => $cart->product_id])->first();
    //         $is_stock   = $this->stock->get_product_stock($cart->product_id) > 0 ? 1 : 0;
            
    //         if($is_stock == 1){
    //             if($cart->collection_id > 0){
                    
    //                 $discount = $collection->price - $collection->sale_price;

    //                 $actual_total_amount    += ($collection->price * $cart->quantity);
    //                 $total_amount           += $collection->sale_price * $cart->quantity;
    //                 $total_discount         += $discount * $cart->quantity;;

    //             }else{

    //                 $discount = $product->price - $product->discount_price;

    //                 $actual_total_amount    += ($product->price * $cart->quantity);
    //                 $total_amount           += ($product->discount_price * $cart->quantity);
    //                 $total_discount         += $discount * $cart->quantity;;

    //             }
    //         } 
    //     }

    //     $data = [
    //         'total_amount'      => $actual_total_amount,
    //         'total_payable'     => $total_amount,
    //         'total_discount'    => $total_discount,
    //         'delivery_charge'   => get_setting('delivery_charge'),
    //         'product_count'     => $carts->count(),
    //     ];

    //     return $data;
    // }
}
