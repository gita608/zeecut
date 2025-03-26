<?php

namespace App\Models;


class Banners extends BaseModel
{
    protected $table = 'banners';

    protected $fillable = [
        'title',
        'image',
    ];


}
