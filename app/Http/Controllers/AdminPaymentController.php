<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class AdminPaymentController extends Controller
{
    /**
     * Middleware - Only admin can access
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->is_admin) {
                abort(403, 'Unauthorized access');
            }
            return $next($request);
        });
    }

    /**
     * List all payments for admin dashboard
     */
    public function index()
    {
        $payments = Payment::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show payment details
     */
    public function show($orderId)
    {
        $payments = Payment::where('order_id', $orderId)->with('user')->get();
        
        if ($payments->isEmpty()) {
            abort(404, 'Order not found');
        }

        return view('admin.payments.show', compact('payments', 'orderId'));
    }

    /**
     * Update payment status
     */
    public function updateStatus(Request $request, $orderId)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,confirmed,dikemas,dikirim,selesai,cancelled'
            ]);

            // Log the update attempt
            \Log::info('Attempting to update payment status', [
                'order_id' => $orderId,
                'new_status' => $request->status,
                'user_id' => Auth::id()
            ]);

            // Get payments for the order to gather details (phone, total)
            $payments = Payment::where('order_id', $orderId)->get();

            if ($payments->isEmpty()) {
                \Log::warning('Payment not found for update', ['order_id' => $orderId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan'
                ], 404);
            }

            $updated = Payment::where('order_id', $orderId)->update([
                'status' => $request->status
            ]);

            if ($updated > 0) {
                \Log::info('Payment status updated successfully', [
                    'order_id' => $orderId,
                    'new_status' => $request->status
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Status pesanan berhasil diubah menjadi ' . $request->status
                ], 200);
            }

            \Log::warning('Payment not found for update', ['order_id' => $orderId]);
            
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error on status update', [
                'order_id' => $orderId,
                'errors' => $e->errors()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . json_encode($e->errors())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating payment status', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
