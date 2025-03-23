<?php

namespace App\Models;

class Offer extends BaseModel
{
    protected $table = 'offers';

    protected $fillable = [
        'product_id',
        'discount_percentage',
        'start_date',
        'end_date',
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
