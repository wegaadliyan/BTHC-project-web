@extends('layouts.admin')

@section('content')
<div class="custom-products-section">
    <div class="section-header">
        <h1>Produk Custom</h1>
        <a href="#" class="add-btn">Tambah Produk</a>
    </div>

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
            @foreach($customProducts as $product)
            <tr>
                <td>{{ $product->product_code }}</td>
                <td>
                    <img src="{{ asset('storage/custom-products/' . $product->image) }}" alt="{{ $product->name }}" class="product-img">
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->color }}</td>
                <td>{{ $product->size }}</td>
                <td>{{ $product->charm }}</td>
                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td class="actions">
                    <button class="edit-btn">Edit</button>
                    <button class="delete-btn">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
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
}

.edit-btn {
    background-color: #E6DFD5;
    color: #333;
}

.delete-btn {
    background-color: #FFE4E4;
    color: #FF4444;
}
</style>
@endsection