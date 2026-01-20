@extends('layouts.admin')

@section('content')
<div class="payments-section">
    <h1>Manajemen Pembayaran & Pengiriman</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Category Tabs -->
    <div class="category-tabs">
        <button class="tab-btn active" onclick="showCategory('all')">Semua</button>
        <button class="tab-btn" onclick="showCategory('regular')">Produk Biasa</button>
        <button class="tab-btn" onclick="showCategory('custom')">Produk Custom</button>
    </div>

    <!-- Semua Payments -->
    <div id="all-container" class="payments-container category-container">
        @php
            // Group payments by order_id
            $groupedPayments = $payments->groupBy('order_id');
        @endphp

        @forelse($groupedPayments as $orderId => $items)
            @php
                $firstItem = $items->first();
            @endphp
            <div class="payment-card">
                <div class="card-header">
                    <div>
                        <strong>Order ID:</strong> {{ $orderId }}
                        <span class="order-date">{{ $firstItem->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p><strong>Pelanggan:</strong> {{ $firstItem->user_name }}</p>
                    <p><strong>Email:</strong> {{ $firstItem->user->email ?? '-' }}</p>
                    <p><strong>Total:</strong> Rp {{ number_format($firstItem->total_price, 0, ',', '.') }}</p>
                    <p><strong>Produk:</strong> {{ $firstItem->product_name }} (x{{ $firstItem->quantity }})</p>
                    <p><strong>Alamat Pengiriman:</strong> {{ $firstItem->alamat_detail }}</p>
                    <p><strong>No. Telepon:</strong> {{ $firstItem->alamat_telp ?? '-' }}</p>
                </div>
                <div class="card-footer">
                    <div style="margin-bottom:12px;">
                        <strong>Status Saat Ini:</strong>
                        <span class="status-badge status-{{ $firstItem->status }}">
                            @switch($firstItem->status)
                                @case('pending')
                                    <span style="background:#ffeaa7;color:#d63031;padding:6px 12px;border-radius:4px;display:inline-block;">Menunggu Pembayaran</span>
                                    @break
                                @case('confirmed')
                                    <span style="background:#74b9ff;color:white;padding:6px 12px;border-radius:4px;display:inline-block;">Pembayaran Dikonfirmasi</span>
                                    @break
                                @case('dikemas')
                                    <span style="background:#a29bfe;color:white;padding:6px 12px;border-radius:4px;display:inline-block;">Sedang Dikemas</span>
                                    @break
                                @case('dikirim')
                                    <span style="background:#6c5ce7;color:white;padding:6px 12px;border-radius:4px;display:inline-block;">Sedang Dikirim</span>
                                    @break
                                @case('selesai')
                                    <span style="background:#00b894;color:white;padding:6px 12px;border-radius:4px;display:inline-block;">Selesai</span>
                                    @break
                                @default
                                    <span style="background:#95a5a6;color:white;padding:6px 12px;border-radius:4px;display:inline-block;">{{ ucfirst($firstItem->status) }}</span>
                            @endswitch
                        </span>
                    </div>

                    <div style="margin-top:12px;">
                        <label style="display:block;margin-bottom:6px;font-weight:600;">Ubah Status:</label>
                        <select id="status-{{ $orderId }}" class="status-select" style="padding:8px;border:1px solid #ddd;border-radius:4px;width:100%;">
                            <option value="">-- Pilih Status --</option>
                            <option value="pending" @selected($firstItem->status === 'pending')>Menunggu Pembayaran</option>
                            <option value="confirmed" @selected($firstItem->status === 'confirmed')>Pembayaran Dikonfirmasi</option>
                            <option value="dikemas" @selected($firstItem->status === 'dikemas')>Sedang Dikemas</option>
                            <option value="dikirim" @selected($firstItem->status === 'dikirim')>Sedang Dikirim</option>
                            <option value="selesai" @selected($firstItem->status === 'selesai')>Selesai</option>
                            <option value="cancelled" @selected($firstItem->status === 'cancelled')>Dibatalkan</option>
                        </select>
                        <button class="update-status-btn" onclick="updateStatus('{{ $orderId }}')" style="margin-top:8px;padding:8px 16px;background:#2ecc71;color:white;border:none;border-radius:4px;cursor:pointer;width:100%;font-weight:600;">
                            Simpan Perubahan
                        </button>
                        <form action="{{ route('admin.payments.destroy', $firstItem->id) }}" method="POST" style="margin-top:8px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-status-btn" onclick="return confirm('Yakin ingin menghapus data pembayaran ini?')" style="padding:8px 16px;background:#e74c3c;color:white;border:none;border-radius:4px;cursor:pointer;width:100%;font-weight:600;">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p style="grid-column:1/-1;text-align:center;color:#999;padding:40px;">Belum ada pesanan</p>
        @endforelse
    </div>

    <!-- Regular Products Payment -->
    <div id="regular-container" class="payments-container category-container" style="display:none;">
        @php
            $regularPayments = $payments->filter(function($payment) {
                return !$payment->custom_product_id;
            });
            $regularGrouped = $regularPayments->groupBy('order_id');
        @endphp

        @forelse($regularGrouped as $orderId => $items)
            @php
                $firstItem = $items->first();
            @endphp
            <div class="payment-card">
                <div class="card-header">
                    <div>
                        <strong>Order ID:</strong> {{ $orderId }}
                        <span class="order-date">{{ $firstItem->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p><strong>Pelanggan:</strong> {{ $firstItem->user_name }}</p>
                    <p><strong>Email:</strong> {{ $firstItem->user->email ?? '-' }}</p>
                    <p><strong>Total:</strong> Rp {{ number_format($firstItem->total_price, 0, ',', '.') }}</p>
                    <p><strong>Produk:</strong> {{ $firstItem->product_name }} (x{{ $firstItem->quantity }})</p>
                    <p><strong>Alamat Pengiriman:</strong> {{ $firstItem->alamat_detail }}</p>
                    <p><strong>No. Telepon:</strong> {{ $firstItem->alamat_telp ?? '-' }}</p>
                </div>
                <div class="card-footer">
                    <div style="margin-bottom:12px;">
                        <strong>Status Saat Ini:</strong>
                        <span class="status-badge status-{{ $firstItem->status }}">
                            @switch($firstItem->status)
                                @case('pending')
                                    <span style="background:#ffeaa7;color:#d63031;padding:6px 12px;border-radius:4px;display:inline-block;">Menunggu Pembayaran</span>
                                    @break
                                @case('confirmed')
                                    <span style="background:#74b9ff;color:white;padding:6px 12px;border-radius:4px;display:inline-block;">Pembayaran Dikonfirmasi</span>
                                    @break
                                @case('dikemas')
                                    <span style="background:#a29bfe;color:white;padding:6px 12px;border-radius:4px;display:inline-block;">Sedang Dikemas</span>
                                    @break
                                @case('dikirim')
                                    <span style="background:#6c5ce7;color:white;padding:6px 12px;border-radius:4px;display:inline-block;">Sedang Dikirim</span>
                                    @break
                                @case('selesai')
                                    <span style="background:#00b894;color:white;padding:6px 12px;border-radius:4px;display:inline-block;">Selesai</span>
                                    @break
                                @default
                                    <span style="background:#95a5a6;color:white;padding:6px 12px;border-radius:4px;display:inline-block;">{{ ucfirst($firstItem->status) }}</span>
                            @endswitch
                        </span>
                    </div>

                    <div style="margin-top:12px;">
                        <label style="display:block;margin-bottom:6px;font-weight:600;">Ubah Status:</label>
                        <select id="status-{{ $orderId }}" class="status-select" style="padding:8px;border:1px solid #ddd;border-radius:4px;width:100%;">
                            <option value="">-- Pilih Status --</option>
                            <option value="pending" @selected($firstItem->status === 'pending')>Menunggu Pembayaran</option>
                            <option value="confirmed" @selected($firstItem->status === 'confirmed')>Pembayaran Dikonfirmasi</option>
                            <option value="dikemas" @selected($firstItem->status === 'dikemas')>Sedang Dikemas</option>
                            <option value="dikirim" @selected($firstItem->status === 'dikirim')>Sedang Dikirim</option>
                            <option value="selesai" @selected($firstItem->status === 'selesai')>Selesai</option>
                            <option value="cancelled" @selected($firstItem->status === 'cancelled')>Dibatalkan</option>
                        </select>
                        <button class="update-status-btn" onclick="updateStatus('{{ $orderId }}')" style="margin-top:8px;padding:8px 16px;background:#2ecc71;color:white;border:none;border-radius:4px;cursor:pointer;width:100%;font-weight:600;">
                            Simpan Perubahan
                        </button>
                        <form action="{{ route('admin.payments.destroy', $firstItem->id) }}" method="POST" style="margin-top:8px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-status-btn" onclick="return confirm('Yakin ingin menghapus data pembayaran ini?')" style="padding:8px 16px;background:#e74c3c;color:white;border:none;border-radius:4px;cursor:pointer;width:100%;font-weight:600;">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p style="grid-column:1/-1;text-align:center;color:#999;padding:40px;">Belum ada pesanan produk biasa</p>
        @endforelse
    </div>

    <!-- Custom Products Payment -->
    <div id="custom-container" class="payments-container category-container" style="display:none;">
        @php
            $customPayments = $payments->filter(function($payment) {
                return $payment->custom_product_id;
            });
            $customGrouped = $customPayments->groupBy('order_id');
        @endphp

        @forelse($customGrouped as $orderId => $items)
            @php
                $firstItem = $items->first();
            @endphp
            <div class="payment-card">
                <div class="card-header">
                    <div>
                        <strong>Order ID:</strong> {{ $orderId }}
                        <span class="order-date">{{ $firstItem->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p><strong>Pelanggan:</strong> {{ $firstItem->user_name }}</p>
                    <p><strong>Email:</strong> {{ $firstItem->user->email ?? '-' }}</p>
                    <p><strong>Total:</strong> Rp {{ number_format($firstItem->total_price, 0, ',', '.') }}</p>
                    <p><strong>Produk:</strong> {{ $firstItem->product_name }} (x{{ $firstItem->quantity }})</p>
                    <p><strong>Alamat Pengiriman:</strong> {{ $firstItem->alamat_detail }}</p>
                    <p><strong>No. Telepon:</strong> {{ $firstItem->alamat_telp ?? '-' }}</p>
                </div>
                <div class="card-footer">
                    <div style="margin-bottom:12px;">
                        <strong>Status Saat Ini:</strong>
                        <span class="status-badge status-{{ $firstItem->status }}">
                            @switch($firstItem->status)
                                @case('pending')
                                    <span style="background:#ffeaa7;color:#d63031;padding:6px 12px;border-radius:4px;display:inline-block;">Menunggu Pembayaran</span>
                                    @break
                                @case('confirmed')
                                    <span style="background:#74b9ff;color:white;padding:6px 12px;border-radius:4px;display:inline-block;">Pembayaran Dikonfirmasi</span>
                                    @break
                                @case('dikemas')
                                    <span style="background:#a29bfe;color:white;padding:6px 12px;border-radius:4px;display:inline-block;">Sedang Dikemas</span>
                                    @break
                                @case('dikirim')
                                    <span style="background:#6c5ce7;color:white;padding:6px 12px;border-radius:4px;display:inline-block;">Sedang Dikirim</span>
                                    @break
                                @case('selesai')
                                    <span style="background:#00b894;color:white;padding:6px 12px;border-radius:4px;display:inline-block;">Selesai</span>
                                    @break
                                @default
                                    <span style="background:#95a5a6;color:white;padding:6px 12px;border-radius:4px;display:inline-block;">{{ ucfirst($firstItem->status) }}</span>
                            @endswitch
                        </span>
                    </div>

                    <div style="margin-top:12px;">
                        <label style="display:block;margin-bottom:6px;font-weight:600;">Ubah Status:</label>
                        <select id="status-{{ $orderId }}" class="status-select" style="padding:8px;border:1px solid #ddd;border-radius:4px;width:100%;">
                            <option value="">-- Pilih Status --</option>
                            <option value="pending" @selected($firstItem->status === 'pending')>Menunggu Pembayaran</option>
                            <option value="confirmed" @selected($firstItem->status === 'confirmed')>Pembayaran Dikonfirmasi</option>
                            <option value="dikemas" @selected($firstItem->status === 'dikemas')>Sedang Dikemas</option>
                            <option value="dikirim" @selected($firstItem->status === 'dikirim')>Sedang Dikirim</option>
                            <option value="selesai" @selected($firstItem->status === 'selesai')>Selesai</option>
                            <option value="cancelled" @selected($firstItem->status === 'cancelled')>Dibatalkan</option>
                        </select>
                        <button class="update-status-btn" onclick="updateStatus('{{ $orderId }}')" style="margin-top:8px;padding:8px 16px;background:#2ecc71;color:white;border:none;border-radius:4px;cursor:pointer;width:100%;font-weight:600;">
                            Simpan Perubahan
                        </button>
                        <form action="{{ route('admin.payments.destroy', $firstItem->id) }}" method="POST" style="margin-top:8px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-status-btn" onclick="return confirm('Yakin ingin menghapus data pembayaran ini?')" style="padding:8px 16px;background:#e74c3c;color:white;border:none;border-radius:4px;cursor:pointer;width:100%;font-weight:600;">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p style="grid-column:1/-1;text-align:center;color:#999;padding:40px;">Belum ada pesanan produk custom</p>
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
.payment-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
}
.card-header {
    background-color: #F5F1EB;
    padding: 18px 22px;
    font-weight: 600;
    border-bottom: 1px solid #eee;
}
.order-date {
    display: block;
    font-size: 0.875rem;
    color: #666;
    font-weight: normal;
    margin-top: 4px;
}
.card-body {
    padding: 18px 22px;
    flex: 1;
}
.card-body p {
    margin: 0 0 8px 0;
    font-size: 0.95rem;
    line-height: 1.4;
}
.card-footer {
    padding: 18px 22px;
    border-top: 1px solid #eee;
    background-color: #fafafa;
    margin-top: auto;
}
.status-select {
    font-size: 0.95rem;
}
</style>

<script>
function updateStatus(orderId) {
    const statusSelect = document.getElementById('status-' + orderId);
    const newStatus = statusSelect.value;

    if (!newStatus) {
        alert('Pilih status terlebih dahulu');
        return;
    }

    // Show loading state
    const btn = event.target;
    const originalText = btn.innerText;
    btn.disabled = true;
    btn.innerText = 'Menyimpan...';

    // Build the correct URL without extra slash
    const url = '/admin/payments/' + orderId + '/status';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP error, status: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;

        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerText = originalText;
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    });
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
        document.getElementById('all-container').style.display = 'grid';
    } else if (category === 'regular') {
        document.getElementById('regular-container').style.display = 'grid';
    } else if (category === 'custom') {
        document.getElementById('custom-container').style.display = 'grid';
    }
    
    // Add active class to clicked button
    event.target.classList.add('active');
}
</script>

<style>
.delete-status-btn:hover {
    background-color: #c0392b !important;
}
</style>
@endsection