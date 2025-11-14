<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'date',
        'user_name',
        'product_name',
        'email',
        'price',
        'quantity',
        'subtotal',
        'status'
    ];
}