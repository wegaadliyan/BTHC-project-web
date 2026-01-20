<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomProduct extends Model
{
    protected $fillable = [
        'name',
        'price',
        'image',
        'product_code',
        'size',
        'weight',
        'charm_1',
        'charm_1_image',
        'charm_2',
        'charm_2_image',
        'charm_3',
        'charm_3_image'
    ];
}