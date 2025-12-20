<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Apply admin and auth middleware to all methods in this controller.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all payments and order them by the most recent
        $payments = Payment::orderBy('created_at', 'desc')->get();
        
        // Pass the payments data to the view
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Confirm a payment and update its status.
     * @param  int  $id
     */
    public function confirmPayment($id)
    {
        // Find the specific payment entry that was clicked
        $payment = Payment::findOrFail($id);

        // The user's request mentioned 'pending_payment', but our PaymentController
        // saves the status as 'pending'. We will check for 'pending'.
        if ($payment->status == 'pending') {
            // Find all payment items that belong to the same order and update their status
            Payment::where('order_id', $payment->order_id)
                   ->update(['status' => 'confirmed']);

            return redirect()->route('admin.payments.index')
                             ->with('success', 'Pembayaran untuk Order ID ' . $payment->order_id . ' telah berhasil dikonfirmasi.');
        }

        return redirect()->route('admin.payments.index')
                         ->with('info', 'Pembayaran untuk Order ID ' . $payment->order_id . ' sudah dikonfirmasi sebelumnya.');
    }
}
