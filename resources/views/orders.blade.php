@extends('layouts.app')
@section('content')
<div class="container" style="max-width:900px;margin:40px auto;">
    <h1 class="text-2xl font-bold mb-6">Pesanan Saya</h1>
    @forelse($orders as $order)
        <div style="border:1px solid #ddd;padding:16px;border-radius:6px;margin-bottom:16px;display:flex;justify-content:space-between;align-items:flex-start;gap:20px;">
            <div style="flex:1;">
                <div style="margin-bottom:8px;"><strong>Order ID:</strong> {{ $order->order_id }}</div>
                <div style="margin-bottom:8px;"><strong>Produk:</strong> {{ $order->product_name }}</div>
                <div style="margin-bottom:8px;"><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                <div style="margin-bottom:8px;">
                    <strong>Status:</strong>
                    @if($order->status == 'pending')
                        <span style="background:#ffeaa7;color:#d63031;padding:6px 12px;border-radius:4px;display:inline-block;font-weight:600;">Menunggu Pembayaran</span>
                    @elseif($order->status == 'confirmed')
                        <span style="background:#74b9ff;color:white;padding:6px 12px;border-radius:4px;display:inline-block;font-weight:600;">Pembayaran Dikonfirmasi</span>
                    @elseif($order->status == 'dikemas')
                        <span style="background:#a29bfe;color:white;padding:6px 12px;border-radius:4px;display:inline-block;font-weight:600;">Sedang Dikemas</span>
                    @elseif($order->status == 'dikirim')
                        <span style="background:#6c5ce7;color:white;padding:6px 12px;border-radius:4px;display:inline-block;font-weight:600;">Sedang Dikirim</span>
                    @elseif($order->status == 'selesai')
                        <span style="background:#00b894;color:white;padding:6px 12px;border-radius:4px;display:inline-block;font-weight:600;">Selesai</span>
                    @elseif(strtolower($order->status) == 'cancelled')
                        <span style="background:#e74c3c;color:white;padding:6px 12px;border-radius:4px;display:inline-block;font-weight:600;">Dibatalkan</span>
                    @else
                        <span style="background:#95a5a6;color:white;padding:6px 12px;border-radius:4px;display:inline-block;font-weight:600;">{{ ucfirst($order->status ?? 'Unknown') }}</span>
                    @endif
                </div>
            </div>
            <div style="flex-shrink:0;text-align:right;min-width:150px;">
                @if($order->status == 'pending')
                    <!-- Icon bayar, trigger JS Snap -->
                    <button class="pay-btn" data-order="{{ $order->order_id }}" style="background:none;border:none;cursor:pointer;padding:8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="width:32px;height:32px;color:#22c55e;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2z" />
                        </svg>
                    </button>
                @elseif(strtolower(trim($order->status)) == 'cancelled')
                    @if(!empty($admin_whatsapp))
                        @php
                            $custPhone = $order->alamat_telp ?? '';
                            $total = number_format($order->total_price ?? 0, 0, ',', '.');
                            $message = "Permintaan refund untuk Order ID: {$order->order_id} - Nomor pelanggan: {$custPhone} - Total: Rp {$total}. Mohon bantu proses refund.";
                            $waUrl = 'https://wa.me/' . $admin_whatsapp . '?text=' . urlencode($message);
                        @endphp
                        <a href="{{ $waUrl }}" target="_blank" style="display:inline-block;padding:12px 18px;background:#25D366;color:white;border-radius:6px;font-weight:600;text-decoration:none;cursor:pointer;transition:background-color 0.3s;font-size:14px;border:none;white-space:nowrap;">
                            💬 Hubungi Admin
                        </a>
                    @endif
                @endif
            </div>
        </div>
    @empty
        <div class="text-center text-gray-500 mt-12">Belum ada pesanan.</div>
    @endforelse
</div>
<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.querySelectorAll('.pay-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        var orderId = btn.getAttribute('data-order');
        fetch('/pay', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ order_id: orderId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.snapToken) {
                window.snap.pay(data.snapToken, {
                    onSuccess: function(result){
                        window.location.reload();
                    },
                    onPending: function(result){
                        window.location.reload();
                    },
                    onError: function(result){
                        alert('Pembayaran gagal!');
                    }
                });
            } else {
                alert('Gagal mendapatkan token pembayaran!');
            }
        });
    });
});
</script>
@endsection
