@extends('layouts.app')

@section('content')
<div class="container" style="max-width:900px;margin:40px auto;">
    <h1 style="font-size:2rem;font-weight:700;margin-bottom:32px;">Belum Bayar</h1>
    @if($pendingPayments->isEmpty())
        <div style="text-align:center;margin:48px 0;">
            <img src="/images/empty-cart.png" alt="empty" style="width:120px;opacity:0.7;">
            <p style="font-size:1.2rem;color:#888;margin-top:16px;">Belum ada pesanan yang perlu dibayar.</p>
        </div>
    @else
        @foreach($pendingPayments as $payment)
            <div style="border:1px solid #eee;padding:24px;border-radius:8px;margin-bottom:24px;">
                <h3>Order ID: {{ $payment->order_id }}</h3>
                <p><strong>Produk:</strong> {{ $payment->product_name }}</p>
                <p><strong>Total:</strong> Rp {{ number_format($payment->total_price, 0, ',', '.') }}</p>
                <p><strong>Status:</strong> <span style="color:orange;font-weight:600;">Pending</span></p>
                <form action="{{ route('pay', $payment->order_id) }}" method="GET" style="display:inline-block;">
                    <button type="submit" class="btn btn-primary">Lanjutkan Pembayaran</button>
                </form>
            </div>
        @endforeach
    @endif
</div>
@endsection
