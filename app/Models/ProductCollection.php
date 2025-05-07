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


    public function get_quantity_of_collection($collection_id,$amount){
        $collection = ProductCollection::where(['id' => $collection_id])->first();
        $quantity = $collection->sale_price > 0 ? $amount/$collection->sale_price : 0;

        // $data = [
        //     'sale_price' => $collection->sale_price,
        //     'amount'     => $amount,
        //     'quantity'   => $quantity,
        // ];

        // dd($data);
        return $quantity;
    }

}
