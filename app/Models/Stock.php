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

        return round($out_of_quantity - $consumed_stocks, 2);
     }

    public function get_user_cart_stock_check($cart_quantity, $product_id, $request_quantity) {

        $remaining_stock = $this->get_product_stock($product_id);
        $unit = Product::where('id',$product_id)->first()->unit;
        $unitTypes = [
            1 => 'Kg',
            2 => 'L',
            3 => 'Qty'
        ];
        if ($remaining_stock > 0) {
            $total_cart_quantity = $cart_quantity + $request_quantity;
    
            if ($total_cart_quantity > $remaining_stock) {
                $rounded_stock = round($remaining_stock,2);
                return [
                    'status' => false,
                    'stock' => $rounded_stock,
                    'message' => "Only {$rounded_stock} {$unitTypes[$unit]} left in stock."
                ];
            } else {
                return [
                    'status' => true,
                    'stock' => $remaining_stock,
                    'message' => "Stock available."
                ];
            }
        } else {
            return [
                'status' => false,
                'stock' => 0,
                'message' => "Out of stock."
            ];
        }
    }
    
}
