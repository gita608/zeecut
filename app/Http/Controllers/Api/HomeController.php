<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\User;
use App\Models\Banners;
use App\Models\Product;
use App\Models\Product_images;
use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;

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
        $user = [];
        if($this->userId > 0 || $this->userId != null){
            $user = User::where('id', $this->userId)->first();
        }

        $categories = $this->category->getData();
        foreach ($categories as &$category) {
            $category->icon = $category->icon ? asset('storage/' . $category->icon) : '';
        }

        $banners = $this->banners->getData();
        foreach ($banners as &$banner) {
            $banner->image = $banner->image ? asset('storage/' . $banner->image) : '';
        }

        $offer_products = $this->product->getData(['status' => 1], ['*'], ['created_at' => 'DESC'], 10);
        foreach ($offer_products as &$product) {
            $product->thumbnail = $product->thumbnail ? asset('storage/' . $product->thumbnail) : '';
            $unit_text = $product->unit == 1 ? ' Kg' : ($product->unit == 2 ? ' L' : ' Q');
            $product->unit_text = 1 . $unit_text;
        }

        $cartItems = $this->cart->getData(['user_id' => $this->userId, 'purchase_status' => 0], ['id']);
        $cartCount = count($cartItems);

        $data = [
            'user_data' => $user == null ? [] : $user->userdata(),
            'categories' => $categories,
            'banners' => $banners,
            'offer_products' => $offer_products,
            'cart_count' => $cartCount,
        ];

        return $this->sendSuccessResponse($data, 'Success');
    }

    public function update_notification_token(Request $request)
    {
        $data = [];

        if ($request->notification_token) {
            $data['notification_token'] = $request->notification_token;

            $user = User::find($this->userId);

            if ($user) {
                $user->update($data);
                $message = "Notification Token Updated Successfully";
            } else {
                $message = "User Not Found!!!";
            }
        } else {
            $message = "Notification Token is Required!!!";
        }

        return $this->sendSuccessResponse([], $message);
    }

    public function is_coupon_valid(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendSuccessResponse($validator->errors()->first());
        }

        $coupon = Coupon::where('coupon_code', $request->coupon_code)->first();

        if (!$coupon) {
            $message = ['status' => 0, 'message' => 'Coupon not found.'];
        } else {
            $message = $coupon->getUsabilityMessage($this->userId) ?? 'Coupon is valid and applied.';
        }
        return $this->sendSuccessResponse($message['message']);
    }

}
