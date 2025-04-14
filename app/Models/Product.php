<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

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
        'unit'
    ];

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
