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
}
