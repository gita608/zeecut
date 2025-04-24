<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCollection;
use App\Models\Product_images;
use App\Models\Cart;

class ProductController extends ApiBaseController
{
    protected $product;
    protected $product_collection;
    protected $product_images;
    protected $cart;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->product = new Product();
        $this->product_collection = new ProductCollection();
        $this->product_images = new Product_images();
        $this->cart = new Cart();
    }

    public function index(Request $request)
    {
        $category_id = $request->category_id;

        $conditions['status'] = 1;
        $conditions['category_id'] = $category_id;

        $data = $this->product->getData($conditions);

        foreach ($data as &$val) {
            $val->thumbnail     = $val->thumbnail ? asset('storage/' . $val->thumbnail) : '';
            $unit_text          = $val->unit == 1 ? ' Kg' : ($val->unit == 2 ? ' L' : ' Q');
            $val->unit_text     = 1 . $unit_text;
        }

        return $this->sendSuccessResponse($data, 'Success');
    }

    public function details(Request $request)
    {
        $product_id = $request->product_id;

        $conditions['status'] = 1;
        $conditions['id'] = $product_id;

        $data = $this->product->getData($conditions)->first();
 
        // Set thumbnail full path
        $thumbnail = $data->thumbnail ? asset('storage/' . $data->thumbnail) : '';

        // Fetch product images
        $images = $this->product_images->getData(['product_id' => $product_id]);
        $unit_text = $data->unit == 1 ? ' Kg' : ($data->unit == 2 ? ' L' : ' Q');
        $data->unit_text = 1 . $unit_text;
        $cart_data = $this->cart->getData(['collection_id' => 0, 'product_id' => $product_id, 'user_id' => $this->userId, 'purchase_status' => 0])->first();

        $data->cart_quantity = $cart_data->quantity ?? 0;
        $data->cart_amount = $data->cart_quantity > 0 ? $data->price * $data->cart_quantity :  $data->price;
        // $data->cart_discount = $data->discount_price ?? 0;

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

        $data->images               = $images->values();
        $data->thumbnail            = $thumbnail;
        $data->collections          = $this->product_collection->getData(['product_id' => $product_id]);
        $data->has_collection       = $data->collections->isNotEmpty() ? 1 : 0;


        foreach ($data->collections as &$collection) {

            $cart_data_collection = $this->cart->getData(['collection_id' => $collection->id, 'product_id' => $product_id, 'user_id' => $this->userId, 'purchase_status' => 0])->first();

            $collection->cart_quantity = $cart_data_collection->quantity ?? 0;
            $collection->cart_amount = $cart_data_collection->price ?? 0;
            // $collection->cart_discount = $cart_data_collection->discount_amount ?? 0;
        }


        $cart       = Cart::where(['user_id' => $this->userId, 'purchase_status' => 0]);
        $productIds = $cart->pluck('product_id')->toArray();
        $product    = Product::whereIn('id',$productIds)->get();
 
        $total_amount       = $product->sum('price');
        $total_discount     = $product->sum('discount_price');;
        $discounted_amount  = $total_amount - $total_discount;
        $delivery_charge    = get_setting('delivery_charge');
        $total_payable      = $discounted_amount ;

        $data->cart = [
            'total_amount' => $total_amount,
            'total_discount' => $total_discount,
            'discounted_amount' => $discounted_amount,
            'delivery_charge' => $delivery_charge,
            'total_payable' => $total_payable,
            'product_count' => $cart->count(),
        ];


        return $this->sendSuccessResponse($data, 'Success');
    }


    public function product_search(Request $request)
    {

        $search = $request->search;

        $data = [];
        if ($search) {

            $data = Product::when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })->get();
        }

        return $this->sendSuccessResponse($data, 'Success');
    }

}