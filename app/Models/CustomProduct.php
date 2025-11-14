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
        'color',
        'size',
        'charm'
    ];
}