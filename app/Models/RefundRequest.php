<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'reason',
        'status',
        'admin_note',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user that made this refund request
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin that approved this request
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the payment(s) for this order
     */
    public function payments()
    {
        return Payment::where('order_id', $this->order_id);
    }

    /**
     * Scope: Get pending refund requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get approved refund requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Get rejected refund requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
