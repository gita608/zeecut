<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayLater extends Model
{
    protected $fillable = ['user_id','status','credit_limit'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
