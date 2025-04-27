<?php

namespace App\Models;


class ProductCollection extends BaseModel
{
    protected $table = 'product_collections';

    protected $fillable = [
        'product_id',
        'title',
        'price',
        'sale_price'
    ];


}
