<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'product_id',
        'custom_product_id',
        'product_name',
        'quantity',
        'price',
        'status',
        'order_id',
        'shipping_cost',
        'total_price',
        'alamat_nama',
        'alamat_telp',
        'alamat_provinsi',
        'alamat_kota',
        'alamat_kecamatan',
        'alamat_kodepos',
        'alamat_detail',
        'biteship_order_id',
        'metadata',
    ];

    /**
     * Get the user that owns this payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}