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
}
