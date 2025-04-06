<?php

namespace App\Models;

class Product_images extends BaseModel
{
    protected $table = 'product_images';

    protected $fillable = [
        'product_id',
        'image',
    ];
}
