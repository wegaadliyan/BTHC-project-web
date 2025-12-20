@extends('layouts.app')

@section('content')
<div class="container" style="max-width:900px;margin:40px auto;">
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50" @click="show = false">
            {{ session('success') }}
        </div>
    @endif
    <h1 style="font-size:2rem;font-weight:700;margin-bottom:32px;">Keranjang Saya</h1>
    @if($cartItems->isEmpty())
        <p>Keranjang Anda kosong.</p>
    @else
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f3eee3;">
                    <th style="padding:12px 8px;text-align:left;">Produk</th>
                    <th style="padding:12px 8px;text-align:center;">Jumlah</th>
                    <th style="padding:12px 8px;text-align:right;">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:12px 8px;">
                        @if($item->custom_product_id)
                            <div>
                                <strong>{{ $item->customProduct->name ?? '-' }}</strong>
                                <div style="font-size:0.95em;color:#555;">
                                    @if($item->color) <span>Warna: {{ $item->color }}</span> @endif
                                    @if($item->size) <span> | Ukuran: {{ $item->size }}</span> @endif
                                    @if($item->charm) <span> | Charm: {{ $item->charm }}</span> @endif
                                </div>
                            </div>
                        @else
                            {{ $item->product->name ?? '-' }}
                        @endif
                    </td>
                    <td style="padding:12px 8px;text-align:center;">
                        <form method="POST" action="{{ route('cart.update', $item->id) }}" style="display:inline-flex;align-items:center;gap:8px;">
                            @csrf
                            <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" style="width:32px;height:32px;border-radius:50%;border:1px solid #ccc;background:#fff;font-size:18px;" @if($item->quantity <= 1) disabled style="opacity:0.5;cursor:not-allowed;" @endif>-</button>
                            <span style="min-width:32px;display:inline-block;text-align:center;">{{ $item->quantity }}</span>
                            <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" style="width:32px;height:32px;border-radius:50%;border:1px solid #ccc;background:#fff;font-size:18px;">+</button>
                        </form>
                    </td>
                    <td style="padding:12px 8px;text-align:right;">
                        Rp {{ number_format($item->price, 0, ',', '.') }}
                        <form method="POST" action="{{ route('cart.delete', $item->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:none;border:none;color:#c00;font-size:18px;cursor:pointer;margin-left:12px;" title="Hapus">&#128465;</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:32px;text-align:right;font-size:1.2rem;font-weight:600;">
            Total: Rp {{ number_format($cartItems->sum('price'), 0, ',', '.') }}
        </div>
        <div style="margin-top:32px;text-align:right;">
            <a href="{{ url('/checkout') }}" class="action-button" style="padding:12px 32px;background:#e6dfd5;border-radius:4px;color:#222;font-weight:600;text-decoration:none;">Checkout</a>
        </div>
    @endif
</div>
@endsection
