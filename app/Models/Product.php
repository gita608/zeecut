<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\ProductCollection;

class Product extends BaseModel
{
    protected $table = 'products';

    protected $fillable = [
        'category_id', // Add this line
        'name',
        'description',
        'price',
        'discount_price',
        'thumbnail',
        'no_of_collection',
        'unit',
        'sale_price'
    ];

    public function get_product_details($product_id, $collection_id = null)
    {
        $product = Product::find($product_id);

        if (!$product) {
            return ['error' => 'Product not found'];
        }

        $collection = null;
        if ($collection_id > 0 || $collection_id != null ) {
            $collection = ProductCollection::find($collection_id);
        }

        $unitTypes = [
            1 => 'Kg',
            2 => 'L',
        ];

        $data = [
            'category'              => optional(Category::find($product->category_id))->title ?? '',
            'product'               => $product->name,
            'product_price'         => $product->price,
            'product_discount'      => $product->discount_price,
            'product_sale_price'    => $product->sale_price,
            'collection'            => optional($collection)->title ?? '',
            'collection_price'      => optional($collection)->price ?? '',
            'collection_sale_price' => optional($collection)->sale_price ?? '',
            'unit'                  => $unitTypes[$product->unit] ?? 'Quantity',
            'has_collection'        => $collection ? 1 : 0,
        ];

        return $data;
    }


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function collections()
    {
        return $this->hasMany(ProductCollection::class, 'product_id');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'product_id', 'id');
    }




}
