<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCollection;

class ProductController extends ApiBaseController
{
    protected $product;
    protected $product_collection;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->product = new Product();
        $this->product_collection = new ProductCollection();
        
    }
    
    public function index(Request $request)
    {
        $category_id = $request->category_id;
        $conditions['category_id'] = $category_id;

        $data = $this->product->getData($conditions);

        foreach($data as &$val){
            $val->thumbnail = $val->thumbnail ? asset($val->thumbnail) : '';
        }
        
        return $this->sendSuccessResponse($data, 'Success');
    }

    public function details(Request $request)
    {
        $product_id = $request->product_id;
        $conditions['id'] = $product_id;

        $data = $this->product->getData($conditions)->first();

        $data->thumbnail = $data->thumbnail ? asset($data->thumbnail) : '';
        $data->collections = $this->product_collection->getData(['product_id' => $product_id]);

        return $this->sendSuccessResponse($data, 'Success');
    }

}