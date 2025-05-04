<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 use App\Models\OrderItem;
class Stock extends Model
{
    protected $fillable = ['user_id','product_id','quantity'];

     // Relationship: Stock belongs to a Product
     public function product()
     {
         return $this->belongsTo(Product::class);
     }


    public function get_product_stock($product_id){

        $out_of_quantity = Stock::where('product_id',$product_id)->first()->quantity;
        $consumed_stocks = OrderItem::where('product_id', $product_id)->sum('quantity');

        return $out_of_quantity - $consumed_stocks;

    }

    public function get_user_cart_stock_check($existing_quantity,$product_id,$user_id){

        $remaining_stock = $this->get_product_stock($product_id);
    }
}
