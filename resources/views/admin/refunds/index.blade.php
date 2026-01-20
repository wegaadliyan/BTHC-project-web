@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8" style="max-width:1200px;">
    <h1 style="font-size:2rem;font-weight:700;margin-bottom:32px;">Dashboard Refund Requests</h1>
    
    @if(session('success'))
        <div style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:12px;border-radius:6px;margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif
    
    <!-- Tabs -->
    <div style="display:flex;gap:12px;margin-bottom:24px;border-bottom:2px solid #eee;">
        <a href="{{ route('admin.refunds') }}?status=pending" 
           style="padding:12px 24px;border-bottom:3px solid {{ $status === 'pending' ? '#8e44ad' : 'transparent' }};color:{{ $status === 'pending' ? '#8e44ad' : '#888' }};text-decoration:none;font-weight:{{ $status === 'pending' ? '600' : '500' }};cursor:pointer;">
            ⏳ Pending ({{ $refunds->count() }})
        </a>
        <a href="{{ route('admin.refunds') }}?status=approved" 
           style="padding:12px 24px;border-bottom:3px solid {{ $status === 'approved' ? '#27ae60' : 'transparent' }};color:{{ $status === 'approved' ? '#27ae60' : '#888' }};text-decoration:none;font-weight:{{ $status === 'approved' ? '600' : '500' }};cursor:pointer;">
            ✓ Approved
        </a>
        <a href="{{ route('admin.refunds') }}?status=rejected" 
           style="padding:12px 24px;border-bottom:3px solid {{ $status === 'rejected' ? '#e74c3c' : 'transparent' }};color:{{ $status === 'rejected' ? '#e74c3c' : '#888' }};text-decoration:none;font-weight:{{ $status === 'rejected' ? '600' : '500' }};cursor:pointer;">
            ✕ Rejected
        </a>
        <a href="{{ route('admin.refunds') }}?status=all" 
           style="padding:12px 24px;border-bottom:3px solid {{ $status === 'all' ? '#3498db' : 'transparent' }};color:{{ $status === 'all' ? '#3498db' : '#888' }};text-decoration:none;font-weight:{{ $status === 'all' ? '600' : '500' }};cursor:pointer;">
            📊 Semua
        </a>
    </div>
    
    <!-- Refund Requests Table -->
    @if($refunds->isEmpty())
        <div style="text-align:center;padding:40px;background:#f8f9fa;border-radius:8px;">
            <div style="font-size:1.1rem;color:#888;">Tidak ada refund request</div>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8f9fa;border-bottom:2px solid #ddd;">
                        <th style="padding:16px;text-align:left;font-weight:600;color:#333;">Order ID</th>
                        <th style="padding:16px;text-align:left;font-weight:600;color:#333;">Customer</th>
                        <th style="padding:16px;text-align:left;font-weight:600;color:#333;">Alasan</th>
                        <th style="padding:16px;text-align:left;font-weight:600;color:#333;">Status</th>
                        <th style="padding:16px;text-align:left;font-weight:600;color:#333;">Tanggal</th>
                        <th style="padding:16px;text-align:center;font-weight:600;color:#333;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($refunds as $refund)
                        <tr style="border-bottom:1px solid #eee;transition:background 0.2s;">
                            <td style="padding:16px;color:#333;font-weight:600;">{{ $refund->order_id }}</td>
                            <td style="padding:16px;color:#666;">
                                {{ $refund->user->name }}<br>
                                <span style="font-size:0.85rem;color:#999;">{{ $refund->user->email }}</span>
                            </td>
                            <td style="padding:16px;color:#666;">
                                <div style="max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $refund->reason }}">
                                    {{ substr($refund->reason, 0, 50) }}{{ strlen($refund->reason) > 50 ? '...' : '' }}
                                </div>
                            </td>
                            <td style="padding:16px;">
                                @if($refund->status === 'pending')
                                    <span style="background:#ffeaa7;color:#d63031;padding:6px 12px;border-radius:4px;display:inline-block;font-weight:600;font-size:0.9rem;">Pending</span>
                                @elseif($refund->status === 'approved')
                                    <span style="background:#d4edda;color:#155724;padding:6px 12px;border-radius:4px;display:inline-block;font-weight:600;font-size:0.9rem;">✓ Approved</span>
                                @elseif($refund->status === 'rejected')
                                    <span style="background:#f8d7da;color:#721c24;padding:6px 12px;border-radius:4px;display:inline-block;font-weight:600;font-size:0.9rem;">✕ Rejected</span>
                                @endif
                            </td>
                            <td style="padding:16px;color:#888;font-size:0.9rem;">
                                {{ $refund->created_at->format('d M Y, H:i') }}
                            </td>
                            <td style="padding:16px;text-align:center;">
                                <a href="{{ route('admin.refunds.show', $refund->id) }}" 
                                   style="display:inline-block;padding:8px 16px;background:#3498db;color:white;border-radius:4px;text-decoration:none;font-size:0.9rem;font-weight:600;cursor:pointer;">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<style>
table tr:hover {
    background-color: #f8f9fa !important;
}

@media (max-width: 768px) {
    table {
        font-size: 0.85rem;
    }
    
    th, td {
        padding: 12px !important;
    }
}
</style>
@endsection
