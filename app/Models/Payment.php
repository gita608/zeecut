<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id','user_id','payment_method','pending_amount','pay_later_credit','paid','cash_collected_date','paid_date','status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
}
