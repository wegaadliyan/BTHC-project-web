@extends('layouts.admin')

@section('content')
<div class="payments-section">
    <h1>Daftar Pesanan</h1>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="payments-container">
        @forelse($payments->groupBy('user_id') as $userId => $userPayments)
            @php $user = $userPayments->first(); @endphp
            <div class="user-orders-card">
                <div class="user-header" style="cursor:pointer;font-weight:600;background:#f5f1eb;padding:12px;border-radius:8px;" onclick="toggleOrders('orders-{{ $userId }}')">
                    <strong>Nama Pelanggan:</strong> {{ $user->user_name }}
                </div>
                <div class="user-orders-list" id="orders-{{ $userId }}" style="display:none;">
                    @foreach($userPayments->groupBy('order_id') as $orderId => $items)
                        @php $firstItem = $items->first(); @endphp
                        <div class="payment-card">
                            <div class="card-header">
                                <strong>Order ID:</strong> {{ $orderId }}
                            </div>
                            <div class="card-body">
                                <p><strong>Total Pembayaran:</strong> Rp {{ number_format($firstItem->total_price, 0, ',', '.') }} (termasuk ongkir Rp {{ number_format($firstItem->shipping_cost, 0, ',', '.') }})</p>
                                <p><strong>Alamat:</strong> {{ $firstItem->alamat_detail }}, {{ $firstItem->alamat_kecamatan }}, {{ $firstItem->alamat_kota }}, {{ $firstItem->alamat_provinsi }}, {{ $firstItem->alamat_kodepos }}</p>
                                <p><strong>No. Telepon:</strong> {{ $firstItem->alamat_telp ?? '-' }}</p>
                                <p><strong>Status:</strong> <span class="status-{{ $firstItem->status }}">{{ ucfirst($firstItem->status) }}</span></p>
                                <hr>
                                <h5>Item Pesanan:</h5>
                                <ul>
                                    @foreach($items as $item)
                                        <li>{{ $item->quantity }}x {{ $item->product_name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-footer">
                                @if($firstItem->status == 'pending')
                                    <form action="{{ route('admin.payments.confirm', $firstItem->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="confirm-btn">Konfirmasi Pembayaran</button>
                                    </form>
                                @else
                                    <span class="confirmed-text">Terkonfirmasi</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p>Belum ada pesanan yang masuk.</p>
        @endforelse
    </div>
</div>

<style>
.payments-section {
    padding: 20px;
}
.payments-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.user-orders-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}
.user-header {
    background-color: #F5F1EB;
    font-weight: 600;
    padding: 15px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}
.user-orders-list {
    padding: 0 15px 15px 15px;
}
.payment-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    margin-bottom: 10px;
}
.card-header, .card-body, .card-footer {
    padding: 15px;
}
.card-header {
    background-color: #F5F1EB;
    font-weight: 600;
    border-bottom: 1px solid #eee;
}
.card-body p, .card-body ul {
    margin-bottom: 10px;
}
.card-body ul {
    padding-left: 20px;
}
.card-footer {
    border-top: 1px solid #eee;
    background-color: #f9f9f9;
    text-align: right;
    margin-top: auto;
}
.status-pending {
    color: #f39c12;
    font-weight: bold;
}
.status-confirmed {
    color: #2ecc71;
    font-weight: bold;
}
.confirm-btn {
    padding: 8px 15px;
    background-color: #3498db;
    border: none;
    border-radius: 4px;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s;
}
.confirm-btn:hover {
    background-color: #2980b9;
}
.confirmed-text {
    color: #2ecc71;
    font-weight: 600;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
}
</style>

<script>
function toggleOrders(id) {
    var el = document.getElementById(id);
    if (el.style.display === 'none') {
        el.style.display = 'block';
    } else {
        el.style.display = 'none';
    }
}
</script>
@endsection