<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RefundRequest;
use App\Models\Payment;

class AdminRefundController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display all refund requests with different statuses
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        
        if ($status === 'all') {
            $refunds = RefundRequest::with('user')->orderBy('created_at', 'desc')->get();
        } else {
            $refunds = RefundRequest::with('user')
                ->where('status', $status)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('admin.refunds.index', compact('refunds', 'status'));
    }

    /**
     * Show detail of a refund request
     */
    public function show($id)
    {
        $refund = RefundRequest::with(['user', 'approver'])->findOrFail($id);
        
        // Get related order payments
        $payments = Payment::where('order_id', $refund->order_id)->get();

        return view('admin.refunds.show', compact('refund', 'payments'));
    }

    /**
     * Approve refund request
     */
    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'note' => 'nullable|string|max:500'
        ]);

        try {
            $refund = RefundRequest::findOrFail($id);

            if ($refund->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Request ini sudah diproses sebelumnya'
                ], 400);
            }

            // Update refund request
            $refund->update([
                'status' => 'approved',
                'admin_note' => $validated['note'] ?? null,
                'approved_at' => now(),
                'approved_by' => Auth::id()
            ]);

            // Update payment status to "cancelled" so customer can request refund via WhatsApp
            Payment::where('order_id', $refund->order_id)
                ->update(['status' => 'cancelled']);

            \Log::info('Refund request approved', [
                'refund_id' => $id,
                'order_id' => $refund->order_id,
                'approved_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Refund request disetujui. Status pesanan diubah menjadi "Dibatalkan".'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error approving refund', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject refund request
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'note' => 'required|string|min:5|max:500'
        ]);

        try {
            $refund = RefundRequest::findOrFail($id);

            if ($refund->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Request ini sudah diproses sebelumnya'
                ], 400);
            }

            // Update refund request
            $refund->update([
                'status' => 'rejected',
                'admin_note' => $validated['note'],
                'approved_at' => now(),
                'approved_by' => Auth::id()
            ]);

            \Log::info('Refund request rejected', [
                'refund_id' => $id,
                'order_id' => $refund->order_id,
                'rejected_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Refund request ditolak.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error rejecting refund', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
