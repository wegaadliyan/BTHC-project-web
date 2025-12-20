@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 900px; margin: 40px auto; text-align: center;">
    <div style="background-color: #d4edda; color: #155724; padding: 20px; border-radius: 8px;">
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 16px;">Pembayaran Berhasil!</h1>
        <p style="font-size: 1.2rem; margin-bottom: 8px;">Terima kasih telah berbelanja. Pesanan Anda sedang diproses.</p>
        @if(isset($orderId))
            <p><strong>Order ID Anda:</strong> {{ $orderId }}</p>
        @endif
    </div>
    <a href="{{ url('/shop') }}" style="display: inline-block; margin-top: 32px; background-color: #8e44ad; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; text-decoration: none;">Kembali ke Toko</a>
</div>
@endsection
