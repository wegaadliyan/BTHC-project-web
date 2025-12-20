<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'custom_product_id',
        'quantity',
        'price',
        'color',
        'size',
        'charm',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function customProduct()
    {
        return $this->belongsTo(\App\Models\CustomProduct::class, 'custom_product_id');
    }
}
