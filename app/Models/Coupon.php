<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'coupon_code', 'status', 'start_date', 'end_date', 'usage_limit', 'per_user_limit'
    ];

    protected $dates = ['start_date', 'end_date'];
}
