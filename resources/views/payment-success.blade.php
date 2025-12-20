@extends('layouts.app')

@section('content')
<div class="container" style="max-width:900px; margin:40px auto; text-align: center;">
    <h1 style="font-size:2rem; font-weight:700; margin-bottom:24px; color: #2ecc71;">Pembayaran Berhasil!</h1>
    <p style="font-size:1.2rem; margin-bottom:32px;">Terima kasih telah berbelanja. Pesanan Anda sedang diproses.</p>
    <a href="{{ url('/shop') }}" style="background-color:#8e44ad; color:white; padding:12px 24px; border:none; border-radius:8px; font-size:1rem; cursor:pointer; text-decoration:none;">Kembali ke Toko</a>
</div>
@endsection
