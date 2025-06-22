<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Payment;
use \App\Models\CouponUsage;

class Coupon extends Model
{
    protected $fillable = [
        'coupon_code',
        'status',
        'start_date',
        'end_date',
        'usage_limit',
        'per_user_limit'
    ];

    protected $dates = ['start_date', 'end_date'];

    public function getUsabilityMessage($userId)
    {
        $now = now();

        if ($this->status !== 'active') {
            return ['status' => 0, 'message' => 'Coupon is inactive.'];
        }

        if ($this->start_date > $now || $this->end_date < $now) {
            return ['status' => 0, 'message' => 'Coupon is not valid at this time.'];
        }

        if ($this->usage_limit !== null && $this->payments()->count() >= $this->usage_limit) {
            return ['status' => 0, 'message' => 'Coupon usage limit reached.'];
        }

        $userUsage = $this->payments()->where('user_id', $userId)->count();
        if ($this->per_user_limit !== null && $userUsage >= $this->per_user_limit) {
            return ['status' => 0, 'message' => 'You have already used this coupon.'];
        }

        return ['status' => 1, 'message' => 'coupon is used.']; // means it's valid
    }


    // Define relation to payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
