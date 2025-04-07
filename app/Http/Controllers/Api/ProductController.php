<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCollection;
use App\Models\Product_images;

class ProductController extends ApiBaseController
{
    protected $product;
    protected $product_collection;
    protected $product_images;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->product = new Product();
        $this->product_collection = new ProductCollection();
        $this->product_images = new Product_images();
    }
    
    public function index(Request $request)
    {
        $category_id = $request->category_id;
        $conditions['category_id'] = $category_id;

        $data = $this->product->getData($conditions);

        foreach($data as &$val){
            $val->thumbnail = $val->thumbnail ? asset('storage/' . $val->thumbnail) : '';
        }
        
        return $this->sendSuccessResponse($data, 'Success');
    }

    public function details(Request $request)
    {
        $product_id = $request->product_id;
        $conditions['id'] = $product_id;

        $data = $this->product->getData($conditions)->first();

        // Set thumbnail full path
        $thumbnail = $data->thumbnail ? asset('storage/' . $data->thumbnail) : '';

        // Fetch product images
        $images = $this->product_images->getData(['product_id' => $product_id]);

        // Convert all image URLs to full path
        $images = $images->map(function ($img) {
            $img->image = asset('storage/' . $img->image);
            return $img;
        });

        // Check if thumbnail is already in the image list
        $exists = $images->contains(function ($img) use ($thumbnail) {
            return $img->image === $thumbnail;
        });

        if ($thumbnail && !$exists) {
            $thumbObj = new \stdClass();
            $thumbObj->image = $thumbnail;
            $thumbObj->is_primary = true;
            $images->prepend($thumbObj);
        }

        $data->images = $images->values(); // reset keys
        $data->thumbnail = $thumbnail;
        $data->collections = $this->product_collection->getData(['product_id' => $product_id]);

        return $this->sendSuccessResponse($data, 'Success');
    }

}