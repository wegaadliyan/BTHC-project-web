@extends('layouts.admin')

@section('content')
<div class="custom-product-create-section">
    <div class="section-header">
        <h1>Tambah Produk Custom</h1>
    </div>

    <form action="{{ route('admin.custom-products.store') }}" method="POST" enctype="multipart/form-data" class="form-container">
        @csrf
        <div class="form-group">
            <label for="product_code">Kode Produk</label>
            <input type="text" id="product_code" name="product_code" required value="{{ old('product_code') }}">
            @error('product_code')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="name">Nama Produk</label>
            <input type="text" id="name" name="name" required value="{{ old('name') }}">
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="price">Harga</label>
            <input type="number" id="price" name="price" required value="{{ old('price') }}">
            @error('price')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="color">Warna</label>
            <input type="text" id="color" name="color" value="{{ old('color') }}">
        </div>

        <div class="form-group">
            <label for="size">Ukuran</label>
            <input type="text" id="size" name="size" value="{{ old('size') }}">
        </div>

        <div class="form-group">
            <label for="charm">Charm</label>
            <input type="text" id="charm" name="charm" value="{{ old('charm') }}">
        </div>

        <div class="form-group">
            <label for="image">Gambar Produk</label>
            <input type="file" id="image" name="image" accept="image/*">
            @error('image')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Simpan</button>
            <a href="{{ route('admin.custom-products') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>

<style>
.custom-product-create-section {
    padding: 20px;
}

.section-header {
    margin-bottom: 30px;
}

.section-header h1 {
    font-size: 28px;
    font-weight: 600;
}

.form-container {
    background: white;
    padding: 30px;
    border-radius: 8px;
    max-width: 600px;
}

.form-group {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-group input {
    padding: 10px 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

.form-group input:focus {
    outline: none;
    border-color: #D2B893;
    box-shadow: 0 0 0 2px rgba(210, 184, 147, 0.1);
}

.error {
    color: #FF4444;
    font-size: 12px;
    margin-top: 4px;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
}

.btn-submit,
.btn-cancel {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.btn-submit {
    background-color: #D2B893;
    color: white;
}

.btn-submit:hover {
    background-color: #c4a878;
}

.btn-cancel {
    background-color: #e0e0e0;
    color: #333;
}

.btn-cancel:hover {
    background-color: #d0d0d0;
}
</style>
@endsection
