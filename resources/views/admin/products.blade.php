@extends('layouts.admin')

@section('content')
<div class="products-section">
    <div class="section-header">
        <h1>Produk</h1>
        <a href="#" class="add-btn">Tambah Produk</a>
    </div>

    <table class="products-table">
        <thead>
            <tr>
                <th>ID Produk</th>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->product_code }}</td>
                <td>
                    <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="product-img">
                </td>
                <td>{{ $product->name }}</td>
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
.products-section {
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

.products-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.products-table th,
.products-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.products-table th {
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