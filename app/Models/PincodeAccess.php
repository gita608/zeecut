<?php

namespace App\Models;

class PincodeAccess extends BaseModel
{
    protected $table = 'pincode_access';

    protected $fillable = [
        'name',
        'pincode',
    ];
}
