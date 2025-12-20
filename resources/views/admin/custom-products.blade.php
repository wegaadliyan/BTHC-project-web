@extends('layouts.admin')

@section('content')
<div class="custom-products-section">
    <div class="section-header">
        <h1>Produk Custom</h1>
        <a href="{{ route('admin.custom-products.create') }}" class="add-btn">Tambah Produk</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="custom-products-table">
        <thead>
            <tr>
                <th>ID Produk</th>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Warna</th>
                <th>Ukuran</th>
                <th>Charm</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customProducts as $product)
            <tr>
                <td>{{ $product->product_code }}</td>
                <td>
                    <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="product-img">
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->color ?? '-' }}</td>
                <td>{{ $product->size ?? '-' }}</td>
                <td>{{ $product->charm ?? '-' }}</td>
                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td class="actions">
                    <a href="{{ route('admin.custom-products.edit', $product->id) }}" class="edit-btn">Edit</a>
                    <form action="{{ route('admin.custom-products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px;">Tidak ada produk custom</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
.alert {
    padding: 12px 16px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.custom-products-section {
    padding: 20px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.add-btn {
    padding: 10px 20px;
    background-color: #E6DFD5;
    border: none;
    border-radius: 5px;
    color: #333;
    text-decoration: none;
    cursor: pointer;
}

.add-btn:hover {
    background-color: #ddd3ca;
}

.custom-products-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.custom-products-table th,
.custom-products-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.custom-products-table th {
    background-color: #F5F1EB;
    font-weight: 600;
}

.product-img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
}

.actions {
    display: flex;
    gap: 10px;
}

.edit-btn,
.delete-btn {
    padding: 5px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    font-size: 14px;
}

.edit-btn {
    background-color: #E6DFD5;
    color: #333;
}

.edit-btn:hover {
    background-color: #ddd3ca;
}

.delete-btn {
    background-color: #FFE4E4;
    color: #FF4444;
}

.delete-btn:hover {
    background-color: #ffd4d4;
}
</style>
@endsection