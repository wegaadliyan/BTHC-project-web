@extends('layouts.admin')

@section('content')
<div class="payments-section">
    <h1>Daftar Pesanan</h1>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <!-- Category Tabs -->
    <div class="category-tabs">
        <button class="tab-btn active" onclick="showCategory('all')">Semua</button>
        <button class="tab-btn" onclick="showCategory('regular')">Produk Biasa</button>
        <button class="tab-btn" onclick="showCategory('custom')">Produk Custom</button>
    </div>

    <!-- Semua Payment -->
    <div id="all-container" class="payments-container category-container">
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
                                        @php
                                            $kategori = $item->custom_product_id ? 'Custom' : 'Produk Biasa';
                                            $kategoriClass = $item->custom_product_id ? 'custom-product' : 'regular-product';
                                        @endphp
                                        <li>
                                            {{ $item->quantity }}x {{ $item->product_name }}
                                            <span class="product-kategori kategori-{{ $kategoriClass }}">{{ $kategori }}</span>
                                        </li>
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
                                <form action="{{ route('admin.payments.destroy', $firstItem->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn" onclick="return confirm('Yakin ingin menghapus data pembayaran ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p>Belum ada pesanan yang masuk.</p>
        @endforelse
    </div>

    <!-- Regular Products Payment -->
    <div id="regular-container" class="payments-container category-container" style="display:none;">
        @php
            $regularPayments = $payments->filter(function($payment) {
                return !$payment->custom_product_id;
            });
            $regularGrouped = $regularPayments->groupBy('user_id');
        @endphp
        @forelse($regularGrouped as $userId => $userPayments)
            @php $user = $userPayments->first(); @endphp
            <div class="user-orders-card">
                <div class="user-header" style="cursor:pointer;font-weight:600;background:#f5f1eb;padding:12px;border-radius:8px;" onclick="toggleOrders('regular-orders-{{ $userId }}')">
                    <strong>Nama Pelanggan:</strong> {{ $user->user_name }}
                </div>
                <div class="user-orders-list" id="regular-orders-{{ $userId }}" style="display:none;">
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
                                <form action="{{ route('admin.payments.destroy', $firstItem->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn" onclick="return confirm('Yakin ingin menghapus data pembayaran ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p>Belum ada pesanan produk biasa.</p>
        @endforelse
    </div>

    <!-- Custom Products Payment -->
    <div id="custom-container" class="payments-container category-container" style="display:none;">
        @php
            $customPayments = $payments->filter(function($payment) {
                return $payment->custom_product_id;
            });
            $customGrouped = $customPayments->groupBy('user_id');
        @endphp
        @forelse($customGrouped as $userId => $userPayments)
            @php $user = $userPayments->first(); @endphp
            <div class="user-orders-card">
                <div class="user-header" style="cursor:pointer;font-weight:600;background:#f5f1eb;padding:12px;border-radius:8px;" onclick="toggleOrders('custom-orders-{{ $userId }}')">
                    <strong>Nama Pelanggan:</strong> {{ $user->user_name }}
                </div>
                <div class="user-orders-list" id="custom-orders-{{ $userId }}" style="display:none;">
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
                                <form action="{{ route('admin.payments.destroy', $firstItem->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn" onclick="return confirm('Yakin ingin menghapus data pembayaran ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p>Belum ada pesanan produk custom.</p>
        @endforelse
    </div>
</div>

<style>
.payments-section {
    padding: 20px;
}

.category-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.tab-btn {
    padding: 10px 20px;
    background-color: #e8e3d9;
    border: 2px solid #d4ccc2;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    color: #333;
    transition: all 0.3s ease;
}

.tab-btn:hover {
    background-color: #ddd3ca;
    border-color: #c9bfb5;
}

.tab-btn.active {
    background-color: #c9b8a7;
    border-color: #a89a8b;
    color: white;
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
.delete-btn {
    padding: 8px 15px;
    background-color: #e74c3c;
    border: none;
    border-radius: 4px;
    color: white;
    cursor: pointer;
    margin-left: 8px;
    transition: background-color 0.3s;
}
.delete-btn:hover {
    background-color: #c0392b;
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
.product-kategori {
    display: inline-block;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 4px;
    margin-left: 8px;
    font-weight: 600;
}
.kategori-custom-product {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
}
.kategori-regular-product {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
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

function showCategory(category) {
    // Hide all containers
    document.getElementById('all-container').style.display = 'none';
    document.getElementById('regular-container').style.display = 'none';
    document.getElementById('custom-container').style.display = 'none';
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected container
    if (category === 'all') {
        document.getElementById('all-container').style.display = 'block';
    } else if (category === 'regular') {
        document.getElementById('regular-container').style.display = 'block';
    } else if (category === 'custom') {
        document.getElementById('custom-container').style.display = 'block';
    }
    
    // Add active class to clicked button
    event.target.classList.add('active');
}
</script>
@endsection