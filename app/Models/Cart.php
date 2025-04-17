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


    // Cart.php
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
