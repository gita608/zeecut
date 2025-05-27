<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Payment;

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

    public function isUsableByUser($userId)
    {
        $now = now();

        // Check if coupon is active and within date
        if ($this->status !== 'active' || $this->start_date > $now || $this->end_date < $now) {
            return false;
        }

        // Check global usage limit
        if ($this->usage_limit !== null && $this->payments()->count() >= $this->usage_limit) {
            return false;
        }

        // Check per user usage
        $userUsage = $this->payments()->where('user_id', $userId)->count();
        if ($this->per_user_limit !== null && $userUsage >= $this->per_user_limit) {
            return false;
        }

        return true;
    }

    // Define relation to payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
