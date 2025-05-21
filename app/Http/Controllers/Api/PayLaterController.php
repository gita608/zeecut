<?php

namespace App\Http\Controllers\Api;

use App\Models\PayLater;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class PayLaterController extends ApiBaseController
{
    protected $product;

    public function __construct(Request $request){

        $this->product = new Product;
    }


    public function index(Request $request)
    {
        $userCredit     = user_credit($this->userId);
        $creditBalance  = credit_balance($this->userId);


        $data = [
            'user_limit'      => $userCredit,
            'balance_amount'  => $creditBalance,
            'paid_amount'     => $userCredit - $creditBalance
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
