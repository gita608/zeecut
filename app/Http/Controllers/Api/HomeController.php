<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\User;
use App\Models\Banners;
use App\Models\Product;
use App\Models\Product_images;
use App\Models\Cart;

class HomeController extends ApiBaseController
{
    protected $category;
    protected $user;
    protected $banners;
    protected $product;
    protected $product_images;
    protected $cart;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->category = new Categories();
        $this->user = new User();
        $this->banners = new Banners();
        $this->product = new Product();
        $this->product_images = new Product_images();
        $this->cart = new Cart();
    }
    
    public function index(Request $request)
    {
        $user = User::where('id', $this->userId)->first();
        $categories = $this->category->getData();
        foreach($categories as &$category){
            $category->icon = $category->icon ? asset('storage/' . $category->icon) : '';
        }
        $banners = $this->banners->getData();

        foreach($banners as &$banner){
            $banner->image = $banner->image ? asset('storage/' . $banner->image) : '';
        }

        $offer_products = $this->product->getData(
            [],
            ['*'],
            ['created_at' => 'DESC'],
            10
        );
        
        foreach($offer_products as &$product){
            $product->thumbnail = $product->thumbnail ? asset('storage/' . $product->thumbnail) : '';
        }

        $cartItems = $this->cart->getData(
            ['user_id' => $this->userId, 'purchase_status' => 0],
            ['id'] // or any column, since we just want the count
        );
        
        $cartCount = count($cartItems);

        $data = [
            'user_data' => $user->userdata(),
            'categories' => $categories,
            'banners' => $banners,
            'offer_products' => $offer_products,
            'cart_count' => $cartCount,
        ];
        
        return $this->sendSuccessResponse($data, 'Success');
    }


}