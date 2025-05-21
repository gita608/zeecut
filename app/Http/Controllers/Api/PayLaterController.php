<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\User;
use App\Models\Banners;
use App\Models\Product;
use App\Models\Product_images;
use App\Models\Cart;
use App\Models\Payment;

class PayLaterController extends ApiBaseController
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
        $userCredit     = user_credit($this->userId);
        $creditBalance  = credit_balance($this->userId);


        $data = [
            'user_limit'      => $userCredit,
            'balance_amount'  => $creditBalance,
            'used_amount'     => $userCredit - $creditBalance
        ];


        $histories = Payment::where('user_id',$this->userId)
        ->where('payment_method',2)
        ->get();

        $data['history'] = [];

        foreach($histories as $history){
            $items = [];
            $items['order_no']  = $history->order_id;
            $items['amount']    = $history->pay_later_credit;
            $items['status']    = $history->status;
            $data['history'][]  = $items;  // Append instead of overwrite
        }


        return $this->sendSuccessResponse($data, 'Success');
    }

}
