@extends('layouts.admin')

@section('content')
<style>
    .banner-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .banner-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
        color: #333;
    }

    .btn-add {
        background-color: #111111;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-add:hover {
        background-color: #333333;
        color: white;
    }

    .card {
        border: none;
        border-radius: 8px;
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .card-header {
        border-bottom: 1px solid #eee;
        padding: 20px;
        background: #f9f9f9;
    }

    .card-body {
        padding: 20px;
    }

    .table {
        margin: 0;
    }

    .table thead {
        background: #f5f5f5;
    }

    .table-hover tbody tr:hover {
        background: #f9f9f9;
    }

    .badge {
        padding: 6px 10px;
        border-radius: 4px;
    }

    .img-thumbnail {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px;
    }

    .btn {
        padding: 6px 12px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s;
    }

    .btn-warning {
        background-color: #FF9800;
        color: white;
    }

    .btn-warning:hover {
        background-color: #E68A00;
        color: white;
    }

    .btn-danger {
        background-color: #DC3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #C82333;
        color: white;
    }

    .btn-success {
        background-color: #28A745;
        color: white;
    }

    .btn-success:hover {
        background-color: #218838;
        color: white;
    }

    .btn-secondary {
        background-color: #6C757D;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5A6268;
        color: white;
    }

    .alert {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .alert-success {
        background-color: #D4EDDA;
        color: #155724;
        border: 1px solid #C3E6CB;
    }

    .alert-info {
        background-color: #D1ECF1;
        color: #0C5460;
        border: 1px solid #BEE5EB;
    }
</style>

<div class="banner-header">
    <h1>Manajemen Banner Slider</h1>
    <a href="{{ route('admin.banners.create') }}" class="btn-add">
        <i class="fas fa-plus"></i> Tambah Banner
    </a>
</div>

    
@if(session('success'))
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if($banners->count() > 0)
<div class="card">
    <div class="card-header">
        <h5 style="margin: 0;">Daftar Banner Slider</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="bannerTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">Order</th>
                        <th style="width: 150px;">Gambar</th>
                        <th>Alt Text</th>
                        <th>Link</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="bannerTableBody">
                    @foreach($banners as $banner)
                    <tr class="banner-row" data-id="{{ $banner->id }}" style="cursor: grab;">
                        <td>
                            <span class="badge" style="background-color: #007BFF;">{{ $banner->order + 1 }}</span>
                        </td>
                        <td>
                            @if($banner->image)
                            <img src="{{ asset('storage/banners/' . $banner->image) }}" 
                                 alt="{{ $banner->alt_text }}" 
                                 class="img-thumbnail" 
                                 style="max-width: 100px; height: auto;">
                            @else
                            <span style="color: #999;">Tidak ada gambar</span>
                            @endif
                        </td>
                        <td>{{ $banner->alt_text ?? '-' }}</td>
                        <td>
                            @if($banner->link)
                            <a href="{{ $banner->link }}" target="_blank" style="color: #0066cc; text-decoration: none; display: block; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $banner->link }}
                            </a>
                            @else
                            <span style="color: #999;">-</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.banners.toggle', $banner->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn {{ $banner->is_active ? 'btn-success' : 'btn-secondary' }}">
                                    {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('Yakin ingin menghapus banner ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="alert alert-info">
    <h5><i class="fas fa-info-circle"></i> Tidak ada banner</h5>
    <p>Belum ada banner yang ditambahkan. <a href="{{ route('admin.banners.create') }}" style="font-weight: bold;">Tambah banner sekarang</a></p>
</div>
@endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // Enable drag and drop untuk mengatur urutan
    const bannerTableBody = document.getElementById('bannerTableBody');
    if (bannerTableBody) {
        Sortable.create(bannerTableBody, {
            animation: 150,
            ghostClass: 'bg-light',
            onEnd: function(evt) {
                const order = [];
                document.querySelectorAll('.banner-row').forEach((row, index) => {
                    order.push(row.dataset.id);
                });

                // Send AJAX request to update order
                fetch('{{ route("admin.banners.update-order") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order: order })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    }
</script>
@endsection
