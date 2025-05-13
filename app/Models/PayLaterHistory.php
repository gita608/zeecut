<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayLaterHistory extends Model
{
    protected $fillable = ['user_id','pay_later_id','credit'];
}
