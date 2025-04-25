<?php

namespace App\Models;

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
    ];


    public function get_user_cart_data($user_id){

        $carts = Cart::where(['user_id' => $user_id,'purchase_status' => 0])->get();

        $total_amount = 0;
        $total_discount = 0;
        foreach($carts as $cart){

            $collection = ProductCollection::where(['id' => $cart->collection_id])->first();
            $product    = Product::where(['id' => $cart->product_id])->first();

            if($cart->collection_id > 0){
                $total_amount   += $collection->price * $cart->quantity;
                $total_discount += 0;
            }else{
                $total_amount   += ($product->discount_price * $cart->quantity);
                $total_discount += ($product->price - $product->discount_price) * $cart->quantity;
            }
        }

        $data = [
            // 'total_amount' => $total_amount,
            'total_payable'     => $total_amount,
            'total_discount'    => $total_discount,
            'delivery_charge'   => get_setting('delivery_charge'),
            'product_count'     => $carts->count(),
        ];

        return $data;
    }
}
