@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 900px; margin: 40px auto; text-align: center;">
    <div style="background-color: #fff3cd; color: #856404; padding: 20px; border-radius: 8px;">
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 16px;">Pembayaran Tertunda</h1>
        <p style="font-size: 1.2rem; margin-bottom: 8px;">Kami sedang menunggu konfirmasi pembayaran Anda.</p>
        <p>Silakan selesaikan pembayaran Anda sesuai instruksi dari Midtrans.</p>
        @if(isset($orderId))
            <p><strong>Order ID Anda:</strong> {{ $orderId }}</p>
        @endif
    </div>
    <a href="{{ url('/shop') }}" style="display: inline-block; margin-top: 32px; background-color: #8e44ad; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; text-decoration: none;">Kembali ke Toko</a>
</div>
@endsection
