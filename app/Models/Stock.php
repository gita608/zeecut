<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['user_id','product_id','quantity'];

     // Relationship: Stock belongs to a Product
     public function product()
     {
         return $this->belongsTo(Product::class);
     }
}
