<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'cart_id',
        'product_id',
        'collection_id',
        'quantity',
        'price',
        'sale_price'
    ];

    public function order(){
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function product(){

        return $this->belongsTo(Product::class,'product_id','id');
    }
}
