<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Product;
use App\Models\CustomProduct;
use App\Models\User;
use Midtrans;

class PaymentController extends Controller
{
    public function pay(Request $request, $id = null)
    {
        $user = Auth::user();
        
        // Handle GET request - Continue unpaid payment
        if ($request->isMethod('get')) {
            $payment = Payment::where('order_id', $id)
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->firstOrFail();
            
            // Generate Midtrans Snap Token for unpaid order
            Midtrans\Config::$serverKey = config('midtrans.server_key');
            Midtrans\Config::$isProduction = config('midtrans.is_production');
            Midtrans\Config::$isSanitized = true;
            Midtrans\Config::$is3ds = true;
            
            $params = [
                'transaction_details' => [
                    'order_id' => $id,
                    'gross_amount' => (int) $payment->total_price,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $payment->alamat_telp,
                    'billing_address' => [
                        'first_name' => $payment->alamat_nama,
                        'phone' => $payment->alamat_telp,
                        'address' => $payment->alamat_detail,
                        'city' => $payment->alamat_kota,
                        'postal_code' => $payment->alamat_kodepos,
                        'country_code' => 'IDN'
                    ]
                ]
            ];
            
            $snapToken = Midtrans\Snap::getSnapToken($params);
            return view('payment.show', compact('snapToken', 'id'));
        }
        
        // Handle POST request - New checkout
        $cartItems = Cart::with(['product', 'customProduct'])->where('user_id', $user->id)->get();

        // Validasi request dari checkout form
        $request->validate([
            'recipient_name' => 'required|string',
            'recipient_phone' => 'required|string',
            'recipient_email' => 'nullable|email',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'shipping_address' => 'required|string',
            'postal_code' => 'required|numeric',
            'weight' => 'required|numeric',
            'courier_company' => 'required|string',
            'courier_type' => 'required|string',
            'shipping_cost' => 'required|numeric',
        ]);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang tidak boleh kosong.');
        }

        // 1. Hitung total harga
        $cartTotal = $cartItems->sum('price');
        $shippingCost = (int) $request->shipping_cost;
        $grossAmount = $cartTotal + $shippingCost;

        // 2. Buat Order ID unik
        $orderId = 'BTHC-' . time();

        // 3. Simpan data pembayaran ke database
        foreach ($cartItems as $item) {
            $payment = new Payment();
            $payment->fill([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'product_id' => $item->product_id,
                'custom_product_id' => $item->custom_product_id,
                'product_name' => $item->product->name ?? $item->customProduct->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'status' => 'pending',
                'order_id' => $orderId,
                'shipping_cost' => $shippingCost,
                'total_price' => $grossAmount,
                'alamat_nama' => $request->recipient_name,
                'alamat_telp' => $request->recipient_phone,
                'alamat_provinsi' => $request->province,
                'alamat_kota' => $request->city,
                'alamat_kecamatan' => $request->district,
                'alamat_kodepos' => $request->postal_code,
                'alamat_detail' => $request->shipping_address,
            ]);
            $payment->save();
        }

        // 4. Buat order di Biteship (async - akan diproses setelah payment confirmed)
        $biteshipData = [
            'order_id' => $orderId,
            'recipient_name' => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
            'recipient_email' => $request->recipient_email,
            'recipient_address' => $request->shipping_address,
            'postal_code' => $request->postal_code,
            'courier_company' => $request->courier_company,
            'courier_type' => $request->courier_type,
            'weight' => $request->weight,
            'items' => $cartItems->map(function($item) {
                return [
                    'name' => $item->product->name ?? $item->customProduct->name,
                    'description' => $item->product->description ?? 'Custom Product',
                    'quantity' => $item->quantity,
                    'weight' => ($item->product->weight ?? 200) * $item->quantity,
                    'value' => (int) $item->price,
                ];
            })->toArray(),
        ];
        // Store biteship data in payment for later use
        Payment::where('order_id', $orderId)->update(['metadata' => json_encode($biteshipData)]);

        // 5. Konfigurasi Midtrans
        Midtrans\Config::$serverKey = config('midtrans.server_key');
        Midtrans\Config::$isProduction = config('midtrans.is_production');
        Midtrans\Config::$isSanitized = true;
        Midtrans\Config::$is3ds = true;

        // 6. Buat parameter untuk Midtrans
        $item_details = $cartItems->map(function($item) {
            return [
                'id' => $item->product_id ?? $item->custom_product_id,
                'price' => (int) ($item->product->price ?? $item->customProduct->price),
                'quantity' => $item->quantity,
                'name' => substr($item->product->name ?? $item->customProduct->name, 0, 50)
            ];
        })->toArray();

        // Tambahkan ongkir sebagai item terpisah
        $item_details[] = [
            'id' => 'SHIPPING_COST',
            'price' => (int) $shippingCost,
            'quantity' => 1,
            'name' => 'Biaya Pengiriman'
        ];
        
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $request->recipient_phone,
                'billing_address' => [
                    'first_name' => $request->recipient_name,
                    'phone' => $request->recipient_phone,
                    'address' => $request->shipping_address,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'country_code' => 'IDN'
                ]
            ],
            'item_details' => $item_details
        ];

        // 7. Dapatkan Snap Token
        $snapToken = Midtrans\Snap::getSnapToken($params);

        // 8. Hapus keranjang (payment akan diproses di payment.show view)
        Cart::where('user_id', $user->id)->delete();

        // 9. Kirim token ke view
        return view('payment.show', compact('snapToken', 'orderId'));
    }

    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        
        if (!$orderId) {
            return redirect()->route('home')->with('error', 'Order ID tidak ditemukan.');
        }

        // Verifikasi status pembayaran dengan Midtrans
        Midtrans\Config::$serverKey = config('midtrans.server_key');
        Midtrans\Config::$isProduction = config('midtrans.is_production');
        
        try {
            $status = Midtrans\Transaction::status($orderId);
            
            // Jika status pembayaran sudah settlement (berhasil), update ke confirmed
            if ($status->transaction_status === 'settlement' || $status->transaction_status === 'capture') {
                Payment::where('order_id', $orderId)->update(['status' => 'confirmed']);
                
                // Saat payment sukses, buat order di Biteship
                $this->createBiteshipOrder($orderId);
                
                return view('checkout.success', compact('orderId'));
            } else {
                // Jika belum settlement, redirect ke pending
                return redirect()->route('checkout.pending', ['order_id' => $orderId])
                    ->with('message', 'Pembayaran sedang diproses.');
            }
        } catch (\Exception $e) {
            \Log::error('Midtrans verification error: ' . $e->getMessage());
            // Jika verifikasi gagal, anggap pending dulu
            return redirect()->route('checkout.pending', ['order_id' => $orderId])
                ->with('message', 'Mohon tunggu, pembayaran sedang diverifikasi.');
        }
    }

    public function pending(Request $request)
    {
        $orderId = $request->query('order_id');
        return view('checkout.pending', compact('orderId'));
    }

    public function orders()
    {
        $user = Auth::user();
        
        // Group payments by order_id and get the most recent status for each order
        $orders = Payment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('order_id')
            ->map(function($group) {
                // Get first payment as representative
                $order = $group->first();
                // Add all items in this order
                $order->items = $group;
                // Get refund request status for this order
                $refundRequest = \App\Models\RefundRequest::where('order_id', $order->order_id)->first();
                $order->refund_request = $refundRequest;
                return $order;
            });
        
        // Admin WhatsApp number from config (clean digits)
        $adminPhone = config('admin.whatsapp', '') ?: config('admin.phone', '');
        $adminPhoneClean = preg_replace('/[^0-9]/', '', $adminPhone);

        return view('orders', compact('orders'))->with('admin_whatsapp', $adminPhoneClean);
    }

    /**
     * Create order in Biteship
     */
    private function createBiteshipOrder($orderId)
    {
        try {
            $payments = Payment::where('order_id', $orderId)->get();
            if ($payments->isEmpty()) {
                Log::error('Biteship Order: Payment not found for order ' . $orderId);
                return false;
            }

            $firstPayment = $payments->first();
            
            // Get origin configuration from .env
            $originPostalCode = config('biteship.origin.postal_code', '12440');
            $originName = config('biteship.origin.contact_name', 'BTHC Store');
            $originPhone = config('biteship.origin.contact_phone', '');
            $originAddress = config('biteship.origin.address', '');
            $originEmail = config('biteship.origin.contact_email', '');

            // Get shipper configuration from .env
            $shipperName = config('biteship.shipper.contact_name', 'BTHC Store');
            $shipperPhone = config('biteship.shipper.contact_phone', '');
            $shipperEmail = config('biteship.shipper.contact_email', '');
            $shipperOrg = config('biteship.shipper.organization', 'Better Hope Collection');

            // Prepare items for Biteship
            $items = $payments->map(function($payment) {
                return [
                    'name' => $payment->product_name,
                    'description' => $payment->product_name,
                    'quantity' => $payment->quantity,
                    'weight' => 200, // Default weight per item in grams
                    'value' => (int) $payment->price,
                ];
            })->toArray();

            // Create Biteship order payload
            $payload = [
                'shipper_contact_name' => $shipperName,
                'shipper_contact_phone' => $shipperPhone,
                'shipper_contact_email' => $shipperEmail,
                'shipper_organization' => $shipperOrg,
                'origin_contact_name' => $originName,
                'origin_contact_phone' => $originPhone,
                'origin_contact_email' => $originEmail,
                'origin_address' => $originAddress,
                'origin_postal_code' => (int) $originPostalCode,
                'destination_contact_name' => $firstPayment->alamat_nama,
                'destination_contact_phone' => $firstPayment->alamat_telp,
                'destination_address' => $firstPayment->alamat_detail,
                'destination_postal_code' => (int) $firstPayment->alamat_kodepos,
                'destination_note' => 'Pesanan dari BTHC Store',
                'courier_company' => 'jne', // Default - should come from checkout
                'courier_type' => 'reg', // Default - should come from checkout
                'delivery_type' => 'now',
                'order_note' => 'Order ID: ' . $orderId,
                'reference_id' => $orderId,
                'items' => $items,
            ];

            // Send to Biteship API
            $apiKey = config('biteship.api_key');
            $baseUrl = config('biteship.base_url', 'https://api.biteship.com/v1');

            Log::info('Creating Biteship order for ' . $orderId, $payload);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(15)->post("{$baseUrl}/orders", $payload);

            Log::info('Biteship Create Order Response: ' . $response->status());

            if ($response->successful()) {
                $orderData = $response->json();
                Log::info('Biteship order created successfully', $orderData);
                
                // Store Biteship order ID in payment record
                if (isset($orderData['id'])) {
                    Payment::where('order_id', $orderId)
                        ->update(['biteship_order_id' => $orderData['id']]);
                }
                
                return true;
            } else {
                Log::error('Biteship Create Order Failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'order_id' => $orderId
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Biteship Order Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Handle Midtrans payment notification/webhook
     * This is called by Midtrans when payment status changes
     */
    public function handleNotification(Request $request)
    {
        Midtrans\Config::$serverKey = config('midtrans.server_key');
        Midtrans\Config::$isProduction = config('midtrans.is_production');

        try {
            $notif = new Midtrans\Notification();
            
            $orderId = $notif->order_id;
            $transactionStatus = $notif->transaction_status;
            $fraudStatus = $notif->fraud_status;
            
            \Log::info('Midtrans Notification', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);

            // Update payment status based on transaction status
            if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                // Pembayaran berhasil
                if ($fraudStatus === 'accept' || $fraudStatus === 'challenge') {
                    Payment::where('order_id', $orderId)->update(['status' => 'confirmed']);
                    \Log::info('Payment confirmed for order: ' . $orderId);
                    
                    // Create Biteship order when payment is confirmed
                    $this->createBiteshipOrder($orderId);
                }
            } elseif ($transactionStatus === 'deny' || $transactionStatus === 'cancel' || $transactionStatus === 'expire') {
                // Pembayaran gagal atau expired
                Payment::where('order_id', $orderId)->update(['status' => 'failed']);
                \Log::info('Payment failed/cancelled for order: ' . $orderId);
            } elseif ($transactionStatus === 'pending') {
                // Pembayaran masih pending
                Payment::where('order_id', $orderId)->update(['status' => 'pending']);
            }

            return response()->json(['status' => 'ok'], 200);
        } catch (\Exception $e) {
            \Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Request refund - User submits refund request with reason
     */
    public function requestRefund(Request $request, $orderId)
    {
        $user = Auth::user();
        
        \Log::info('RequestRefund called', [
            'user_id' => $user->id,
            'order_id' => $orderId,
            'request_data' => $request->all()
        ]);
        
        // Validate
        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:500'
        ]);

        try {
            // Check if order exists and belongs to user
            $payment = Payment::where('order_id', $orderId)
                ->where('user_id', $user->id)
                ->first();

            if (!$payment) {
                return $this->refundResponse($request, false, 'Pesanan tidak ditemukan', 404);
            }

            // Check if refund request already exists
            $existingRequest = \App\Models\RefundRequest::where('order_id', $orderId)
                ->where('status', 'pending')
                ->first();

            if ($existingRequest) {
                return $this->refundResponse($request, false, 'Anda sudah mengajukan refund request untuk pesanan ini. Silakan tunggu persetujuan admin.', 400);
            }

            // Create refund request
            $refundRequest = \App\Models\RefundRequest::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'reason' => $validated['reason'],
                'status' => 'pending'
            ]);

            \Log::info('Refund request created', [
                'refund_request_id' => $refundRequest->id,
                'order_id' => $orderId,
                'user_id' => $user->id
            ]);

            return $this->refundResponse($request, true, 'Request refund berhasil dikirim. Admin akan meninjau dalam waktu 1x24 jam.', 201);

        } catch (\Exception $e) {
            \Log::error('Refund request error', [
                'error' => $e->getMessage(),
                'order_id' => $orderId
            ]);

            return $this->refundResponse($request, false, 'Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    private function getShippingCost($destination, $weight)
    {
        // Mock function - actual shipping cost comes from checkout form
        return 20000;
    }

    /**
     * Unified response for refund request (JSON or redirect)
     */
    private function refundResponse(Request $request, bool $success, string $message, int $status = 200)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => $success,
                'message' => $message
            ], $status);
        }

        $flashKey = $success ? 'success' : 'error';
        return redirect()->route('orders')->with($flashKey, $message);
    }
}