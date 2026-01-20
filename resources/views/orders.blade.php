@extends('layouts.app')
@section('content')
<div class="container" style="max-width:1100px;margin:40px auto;padding:0 20px;">
    <h1 style="font-size:2rem;font-weight:700;margin-bottom:32px;">Pesanan Saya</h1>
    
    @if(session('success'))
        <div style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:12px;border-radius:6px;margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif
    
    @forelse($orders as $order)
        <div style="border:1px solid #ddd;padding:24px;border-radius:8px;margin-bottom:24px;background:white;box-shadow:0 2px 4px rgba(0,0,0,0.05);">
            <!-- Header Order -->
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:16px;border-bottom:2px solid #f0f0f0;">
                <div>
                    <div style="font-size:0.9rem;color:#888;margin-bottom:4px;">Order ID</div>
                    <div style="font-size:1.1rem;font-weight:700;color:#333;">{{ $order->order_id }}</div>
                    <div style="font-size:0.85rem;color:#888;margin-top:4px;">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</div>
                </div>
                <div style="text-align:right;">
                    <!-- Status normal -->
                    @if($order->status == 'pending')
                        <span style="background:#ffeaa7;color:#d63031;padding:8px 16px;border-radius:6px;display:inline-block;font-weight:600;font-size:0.9rem;">Menunggu Pembayaran</span>
                    @elseif($order->status == 'confirmed')
                        <span style="background:#74b9ff;color:white;padding:8px 16px;border-radius:6px;display:inline-block;font-weight:600;font-size:0.9rem;">✓ Dikonfirmasi</span>
                    @elseif($order->status == 'dikemas')
                        <span style="background:#a29bfe;color:white;padding:8px 16px;border-radius:6px;display:inline-block;font-weight:600;font-size:0.9rem;">📦 Sedang Dikemas</span>
                    @elseif($order->status == 'dikirim')
                        <span style="background:#6c5ce7;color:white;padding:8px 16px;border-radius:6px;display:inline-block;font-weight:600;font-size:0.9rem;">🚚 Sedang Dikirim</span>
                    @elseif($order->status == 'selesai')
                        <span style="background:#00b894;color:white;padding:8px 16px;border-radius:6px;display:inline-block;font-weight:600;font-size:0.9rem;">✓ Selesai</span>
                    @elseif(strtolower($order->status) == 'cancelled')
                        <span style="background:#e74c3c;color:white;padding:8px 16px;border-radius:6px;display:inline-block;font-weight:600;font-size:0.9rem;">✕ Dibatalkan</span>
                    @elseif(strtolower($order->status) == 'failed')
                        <span style="background:#e17055;color:white;padding:8px 16px;border-radius:6px;display:inline-block;font-weight:600;font-size:0.9rem;">✕ Gagal</span>
                    @else
                        <span style="background:#95a5a6;color:white;padding:8px 16px;border-radius:6px;display:inline-block;font-weight:600;font-size:0.9rem;">{{ ucfirst($order->status ?? 'Unknown') }}</span>
                    @endif
                </div>
            </div>
            
            <!-- Items List -->
            <div style="margin-bottom:20px;">
                @foreach($order->items as $item)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #f5f5f5;">
                        <div style="flex:1;">
                            <div style="font-weight:600;color:#333;margin-bottom:4px;">{{ $item->product_name }}</div>
                            <div style="font-size:0.9rem;color:#888;">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </div>
                        <div style="font-weight:600;color:#333;">
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Shipping Info -->
            @if($order->alamat_detail)
                <div style="background:#f8f9fa;padding:16px;border-radius:6px;margin-bottom:20px;">
                    <div style="font-weight:600;margin-bottom:8px;color:#333;">📍 Alamat Pengiriman</div>
                    <div style="font-size:0.9rem;color:#555;line-height:1.6;">
                        <strong>{{ $order->alamat_nama }}</strong><br>
                        {{ $order->alamat_telp }}<br>
                        {{ $order->alamat_detail }}<br>
                        {{ $order->alamat_kecamatan }}, {{ $order->alamat_kota }}<br>
                        {{ $order->alamat_provinsi }}, {{ $order->alamat_kodepos }}
                    </div>
                </div>
            @endif
            
            <!-- Total & Actions -->
            <div style="display:flex;justify-content:space-between;align-items:center;padding-top:16px;border-top:2px solid #f0f0f0;">
                <div>
                    <div style="font-size:0.9rem;color:#888;margin-bottom:4px;">Total Pembayaran</div>
                    <div style="font-size:1.5rem;font-weight:700;color:#8e44ad;">
                        Rp {{ number_format($order->items->sum(function($i) { return $i->price * $i->quantity; }) + $order->shipping_cost, 0, ',', '.') }}
                    </div>
                    @if($order->shipping_cost > 0)
                        <div style="font-size:0.85rem;color:#888;">Termasuk ongkir Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</div>
                    @endif
                </div>
                
                <div style="display:flex;gap:12px;align-items:center;">
                    @if($order->status == 'pending')
                        <!-- Button Bayar -->
                        <button class="pay-btn" data-order="{{ $order->order_id }}" 
                                style="background:#8e44ad;color:white;padding:12px 24px;border:none;border-radius:6px;font-weight:600;cursor:pointer;transition:all 0.3s;font-size:1rem;">
                            💳 Bayar Sekarang
                        </button>
                    @elseif($order->status == 'dikirim' && $order->biteship_order_id)
                        <!-- Button Track -->
                        <a href="/biteship/tracking/{{ $order->biteship_order_id }}" target="_blank"
                           style="display:inline-block;padding:12px 24px;background:#6c5ce7;color:white;border-radius:6px;font-weight:600;text-decoration:none;font-size:1rem;">
                            🚚 Lacak Paket
                        </a>
                    @elseif($order->status == 'selesai')
                        <button disabled style="background:#95a5a6;color:white;padding:12px 24px;border:none;border-radius:6px;font-weight:600;font-size:1rem;cursor:not-allowed;">
                            ✓ Pesanan Selesai
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div style="text-align:center;margin:64px 0;">
            <img src="/images/empty-cart.png" alt="empty" style="width:150px;opacity:0.5;margin-bottom:24px;" onerror="this.style.display='none'">
            <div style="font-size:1.3rem;color:#888;font-weight:500;">Belum ada pesanan</div>
            <p style="color:#aaa;margin-top:8px;">Yuk mulai belanja sekarang!</p>
            <a href="/shop" style="display:inline-block;margin-top:20px;padding:12px 32px;background:#8e44ad;color:white;text-decoration:none;border-radius:6px;font-weight:600;">
                Mulai Belanja
            </a>
        </div>
    @endforelse
</div>

<!-- Midtrans Snap JS -->
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.querySelectorAll('.pay-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        var orderId = btn.getAttribute('data-order');
        
        // Disable button
        btn.disabled = true;
        btn.innerHTML = '⏳ Memuat...';
        
        // Redirect to payment page
        window.location.href = '/pay/' + orderId;
    });
});

// Hover effects
document.querySelectorAll('.pay-btn').forEach(function(btn) {
    btn.addEventListener('mouseenter', function() {
        this.style.background = '#6c2d91';
        this.style.transform = 'scale(1.05)';
    });
    btn.addEventListener('mouseleave', function() {
        this.style.background = '#8e44ad';
        this.style.transform = 'scale(1)';
    });
</script>

<style>
@media (max-width: 768px) {
    .container {
        padding: 0 12px !important;
    }
    
    div[style*="display:flex"] {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 12px !important;
    }
}
</style>
@endsection
