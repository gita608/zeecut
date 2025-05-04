<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\User;
use App\Models\Banners;
use App\Models\Product;
use App\Models\Product_images;
use App\Models\Cart;
use App\Services\FirebaseNotificationService;

class HomeController extends ApiBaseController
{
    protected $category;
    protected $user;
    protected $banners;
    protected $product;
    protected $product_images;
    protected $cart;
    protected $firebaseService;

    public function __construct(Request $request, FirebaseNotificationService $firebaseService)
    {
        parent::__construct($request);
        $this->category = new Categories();
        $this->user = new User();
        $this->banners = new Banners();
        $this->product = new Product();
        $this->product_images = new Product_images();
        $this->cart = new Cart();
        $this->firebaseService = $firebaseService;
    }

    public function index(Request $request)
    {
        $user = User::where('id', $this->userId)->first();
        $categories = $this->category->getData();
        foreach ($categories as &$category) {
            $category->icon = $category->icon ? asset('storage/' . $category->icon) : '';
        }

        $banners = $this->banners->getData();
        foreach ($banners as &$banner) {
            $banner->image = $banner->image ? asset('storage/' . $banner->image) : '';
        }

        $offer_products = $this->product->getData([], ['*'], ['created_at' => 'DESC'], 10);
        foreach ($offer_products as &$product) {
            $product->thumbnail = $product->thumbnail ? asset('storage/' . $product->thumbnail) : '';
            $unit_text = $product->unit == 1 ? ' Kg' : ($product->unit == 2 ? ' L' : ' Q');
            $product->unit_text = 1 . $unit_text;
        }

        $cartItems = $this->cart->getData(['user_id' => $this->userId, 'purchase_status' => 0], ['id']);
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

    public function send_test_notification(Request $request)
    {
        // Use the static token for testing, or allow dynamic input via request
        $deviceToken = $request->input('token', 'f2byrsOPTqKgmAN6xr7gIv:APA91bGyCNF16SpOOoXNNUMAFhEwR2ubAC_PoaZwC0E5_FphRNk9u3ODYcSEPLqh5SI2ZRRXkgJCNHHnm5VpgU-wE0iGEvdycV3BRpFr6-RjLpWWHHcZbuE');
        $title = "Test Notification";
        $body = "This is a test push notification from Laravel backend.";

        $sent = $this->firebaseService->sendPushNotification($deviceToken, $title, $body);

        if ($sent) {
            return $this->sendSuccessResponse([], 'Push Notification Sent Successfully!');
        } else {
            return $this->sendErrorResponse('Failed to Send Push Notification');
        }
    }
}
