<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends BaseModel
{
    protected $table = 'payment_history';

    protected $fillable = [
        'payment_id',
        'amount',
        'paid_at',
    ];
}
