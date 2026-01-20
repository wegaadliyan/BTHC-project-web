@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8" style="max-width:900px;">
    <!-- Back Button -->
    <a href="{{ route('admin.refunds') }}" style="display:inline-block;margin-bottom:24px;padding:8px 16px;background:#3498db;color:white;border-radius:4px;text-decoration:none;font-weight:600;">
        ← Kembali
    </a>
    
    <div style="background:white;border:1px solid #ddd;border-radius:8px;padding:32px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <!-- Header -->
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:32px;padding-bottom:24px;border-bottom:2px solid #eee;">
            <div>
                <h1 style="font-size:2rem;font-weight:700;margin:0;margin-bottom:8px;color:#333;">{{ $refund->order_id }}</h1>
                <div style="font-size:0.9rem;color:#888;">
                    Dibuat: {{ $refund->created_at->format('d M Y, H:i') }}
                </div>
            </div>
            <div>
                @if($refund->status === 'pending')
                    <span style="background:#ffeaa7;color:#d63031;padding:8px 16px;border-radius:4px;display:inline-block;font-weight:600;font-size:1rem;">⏳ Pending</span>
                @elseif($refund->status === 'approved')
                    <span style="background:#d4edda;color:#155724;padding:8px 16px;border-radius:4px;display:inline-block;font-weight:600;font-size:1rem;">✓ Approved</span>
                @elseif($refund->status === 'rejected')
                    <span style="background:#f8d7da;color:#721c24;padding:8px 16px;border-radius:4px;display:inline-block;font-weight:600;font-size:1rem;">✕ Rejected</span>
                @endif
            </div>
        </div>
        
        <!-- Customer Info -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:32px;padding:24px;background:#f8f9fa;border-radius:6px;">
            <div>
                <h3 style="font-weight:600;margin-bottom:8px;color:#333;">Info Customer</h3>
                <p style="margin:0;color:#666;"><strong>{{ $refund->user->name }}</strong></p>
                <p style="margin:0;color:#666;">{{ $refund->user->email }}</p>
                <p style="margin:0;color:#666;">{{ $refund->user->phone ?? '-' }}</p>
            </div>
            <div>
                <h3 style="font-weight:600;margin-bottom:8px;color:#333;">Total Pesanan</h3>
                @php
                    $totalAmount = $payments->sum(function($p) { return $p->price * $p->quantity; }) + $payments->first()?->shipping_cost;
                @endphp
                <p style="margin:0;font-size:1.3rem;font-weight:700;color:#8e44ad;">Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
                <p style="margin:0;color:#888;font-size:0.9rem;">Jumlah item: {{ $payments->sum('quantity') }}</p>
            </div>
        </div>
        
        <!-- Alasan Refund -->
        <div style="margin-bottom:32px;">
            <h3 style="font-weight:600;margin-bottom:12px;color:#333;">Alasan Refund</h3>
            <div style="padding:16px;background:#f8f9fa;border-left:4px solid #e74c3c;border-radius:4px;">
                <p style="margin:0;color:#666;line-height:1.6;">{{ $refund->reason }}</p>
            </div>
        </div>
        
        <!-- Order Items -->
        <div style="margin-bottom:32px;">
            <h3 style="font-weight:600;margin-bottom:12px;color:#333;">Item Pesanan</h3>
            <div style="border:1px solid #ddd;border-radius:6px;overflow:hidden;">
                @foreach($payments as $payment)
                    <div style="padding:16px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <p style="margin:0;font-weight:600;color:#333;">{{ $payment->product_name }}</p>
                            <p style="margin:0;font-size:0.9rem;color:#888;">{{ $payment->quantity }}x @ Rp {{ number_format($payment->price, 0, ',', '.') }}</p>
                        </div>
                        <div style="font-weight:600;color:#333;">
                            Rp {{ number_format($payment->price * $payment->quantity, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Admin Notes (jika sudah diproses) -->
        @if($refund->status !== 'pending')
            <div style="margin-bottom:32px;padding:16px;background:#f8f9fa;border-radius:6px;">
                <h3 style="font-weight:600;margin-bottom:8px;color:#333;">Catatan Admin</h3>
                <p style="margin:0;color:#666;">{{ $refund->admin_note ?? '-' }}</p>
                @if($refund->approver)
                    <p style="margin:8px 0 0 0;font-size:0.85rem;color:#888;">
                        Diproses oleh: <strong>{{ $refund->approver->name }}</strong><br>
                        Tanggal: {{ $refund->approved_at->format('d M Y, H:i') }}
                    </p>
                @endif
            </div>
        @endif
        
        <!-- Action Buttons -->
        @if($refund->status === 'pending')
            <div style="display:flex;gap:12px;padding-top:24px;border-top:1px solid #ddd;">
                <!-- Approve Button -->
                <button class="approve-btn" data-refund-id="{{ $refund->id }}" 
                        style="flex:1;padding:12px 24px;background:#27ae60;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer;font-size:1rem;transition:background 0.3s;">
                    ✓ Setujui Request
                </button>
                
                <!-- Reject Button -->
                <button class="reject-btn" data-refund-id="{{ $refund->id }}" 
                        style="flex:1;padding:12px 24px;background:#e74c3c;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer;font-size:1rem;transition:background 0.3s;">
                    ✕ Tolak Request
                </button>
            </div>
        @else
            <div style="padding:12px;background:#f0f0f0;border-radius:6px;text-align:center;color:#888;">
                Request ini sudah diproses sebelumnya.
            </div>
        @endif
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" style="display:none;position:fixed;z-index:1000;left:0;top:0;width:100%;height:100%;background-color:rgba(0,0,0,0.5);">
    <div style="background-color:#fefefe;margin:15% auto;padding:0;border-radius:8px;width:90%;max-width:400px;box-shadow:0 4px 20px rgba(0,0,0,0.3);">
        <div style="padding:20px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center;">
            <h2 style="margin:0;font-size:1.1rem;color:#333;">Setujui Request Refund?</h2>
            <button onclick="closeApproveModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#999;">✕</button>
        </div>
        
        <div style="padding:24px;">
            <p style="margin:0 0 20px 0;color:#666;">
                Dengan menyetujui request ini, status pesanan akan otomatis berubah menjadi <strong>"Dibatalkan"</strong> dan customer dapat melanjutkan proses refund.
            </p>
            
            <div style="margin-bottom:16px;">
                <label style="display:block;font-weight:600;margin-bottom:8px;color:#333;">Catatan (Opsional)</label>
                <textarea id="approveNote" placeholder="Tambahkan catatan..." 
                          style="width:100%;border:1px solid #ddd;border-radius:6px;padding:12px;font-family:Arial;font-size:0.95rem;resize:vertical;min-height:80px;box-sizing:border-box;"></textarea>
            </div>
            
            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <button onclick="closeApproveModal()" 
                        style="padding:10px 20px;background:#95a5a6;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer;">
                    Batal
                </button>
                <button onclick="submitApprove()" 
                        style="padding:10px 20px;background:#27ae60;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer;">
                    ✓ Setujui
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" style="display:none;position:fixed;z-index:1000;left:0;top:0;width:100%;height:100%;background-color:rgba(0,0,0,0.5);">
    <div style="background-color:#fefefe;margin:15% auto;padding:0;border-radius:8px;width:90%;max-width:400px;box-shadow:0 4px 20px rgba(0,0,0,0.3);">
        <div style="padding:20px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center;">
            <h2 style="margin:0;font-size:1.1rem;color:#333;">Tolak Request Refund?</h2>
            <button onclick="closeRejectModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#999;">✕</button>
        </div>
        
        <div style="padding:24px;">
            <p style="margin:0 0 20px 0;color:#666;">
                Jelaskan alasan penolakan request refund ini. Customer akan mendapat notifikasi.
            </p>
            
            <div style="margin-bottom:16px;">
                <label style="display:block;font-weight:600;margin-bottom:8px;color:#333;">Alasan Penolakan <span style="color:red;">*</span></label>
                <textarea id="rejectNote" placeholder="Jelaskan alasan penolakan..." 
                          style="width:100%;border:1px solid #ddd;border-radius:6px;padding:12px;font-family:Arial;font-size:0.95rem;resize:vertical;min-height:100px;box-sizing:border-box;"></textarea>
                <div style="font-size:0.8rem;color:#888;margin-top:4px;" id="rejectCharCount">0/500 karakter</div>
            </div>
            
            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <button onclick="closeRejectModal()" 
                        style="padding:10px 20px;background:#95a5a6;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer;">
                    Batal
                </button>
                <button onclick="submitReject()" 
                        style="padding:10px 20px;background:#e74c3c;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer;">
                    ✕ Tolak
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentRefundId = null;

// Approve button
document.querySelector('.approve-btn').addEventListener('click', function() {
    currentRefundId = this.getAttribute('data-refund-id');
    document.getElementById('approveModal').style.display = 'block';
});

// Reject button
document.querySelector('.reject-btn').addEventListener('click', function() {
    currentRefundId = this.getAttribute('data-refund-id');
    document.getElementById('rejectModal').style.display = 'block';
});

function closeApproveModal() {
    document.getElementById('approveModal').style.display = 'none';
    document.getElementById('approveNote').value = '';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejectNote').value = '';
}

function submitApprove() {
    const note = document.getElementById('approveNote').value.trim();
    const btn = event.target;
    
    btn.disabled = true;
    btn.innerHTML = '⏳ Menyimpan...';
    
    fetch('/admin/refunds/' + currentRefundId + '/approve', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            note: note
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan: ' + error.message);
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '✓ Setujui';
    });
}

function submitReject() {
    const note = document.getElementById('rejectNote').value.trim();
    const btn = event.target;
    
    if (!note) {
        alert('Alasan penolakan harus diisi!');
        return;
    }
    
    if (note.length < 5) {
        alert('Alasan minimal 5 karakter!');
        return;
    }
    
    btn.disabled = true;
    btn.innerHTML = '⏳ Menyimpan...';
    
    fetch('/admin/refunds/' + currentRefundId + '/reject', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            note: note
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan: ' + error.message);
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '✕ Tolak';
    });
}

// Character counter for reject note
document.getElementById('rejectNote').addEventListener('input', function() {
    document.getElementById('rejectCharCount').textContent = this.value.length + '/500 karakter';
});

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    const approveModal = document.getElementById('approveModal');
    const rejectModal = document.getElementById('rejectModal');
    if (e.target == approveModal) closeApproveModal();
    if (e.target == rejectModal) closeRejectModal();
});
</script>

<style>
button:hover:not(:disabled) {
    opacity: 0.9;
}

button:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}
</style>
@endsection
