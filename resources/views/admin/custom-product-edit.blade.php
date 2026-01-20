@extends('layouts.admin')

@section('content')
<div class="custom-product-edit-section">
    <div class="section-header">
        <h1>Edit Produk Custom</h1>
    </div>

    <form action="{{ route('admin.custom-products.update', $customProduct->id) }}" method="POST" enctype="multipart/form-data" class="form-container">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="product_code">Kode Produk</label>
            <input type="text" id="product_code" name="product_code" required value="{{ old('product_code', $customProduct->product_code) }}">
            @error('product_code')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="name">Nama Produk</label>
            <input type="text" id="name" name="name" required value="{{ old('name', $customProduct->name) }}">
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="price">Harga</label>
            <input type="number" id="price" name="price" required value="{{ old('price', $customProduct->price) }}">
            @error('price')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="size">Ukuran</label>
            <input type="text" id="size" name="size" value="{{ old('size', $customProduct->size) }}">
        </div>

        <div class="form-group">
            <label for="weight">Berat (gram)</label>
            <input type="number" id="weight" name="weight" value="{{ old('weight', $customProduct->weight) }}" placeholder="Contoh: 500">
        </div>

        <!-- Charm 1 -->
        <div class="form-group">
            <label for="charm_1">Charm 1 (Nama)</label>
            <input type="text" id="charm_1" name="charm_1" value="{{ old('charm_1', $customProduct->charm_1) }}">
        </div>

        <div class="form-group">
            <label for="charm_1_image">Foto Charm 1</label>
            @if($customProduct->charm_1_image)
                <div style="margin-bottom:10px;">
                    <img src="{{ asset('storage/products/' . $customProduct->charm_1_image) }}" alt="Charm 1" style="width:80px;height:80px;object-fit:cover;border-radius:4px;">
                </div>
            @endif
            <input type="file" id="charm_1_image" name="charm_1_image" accept="image/*">
            @error('charm_1_image')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Charm 2 -->
        <div class="form-group">
            <label for="charm_2">Charm 2 (Nama)</label>
            <input type="text" id="charm_2" name="charm_2" value="{{ old('charm_2', $customProduct->charm_2) }}">
        </div>

        <div class="form-group">
            <label for="charm_2_image">Foto Charm 2</label>
            @if($customProduct->charm_2_image)
                <div style="margin-bottom:10px;">
                    <img src="{{ asset('storage/products/' . $customProduct->charm_2_image) }}" alt="Charm 2" style="width:80px;height:80px;object-fit:cover;border-radius:4px;">
                </div>
            @endif
            <input type="file" id="charm_2_image" name="charm_2_image" accept="image/*">
            @error('charm_2_image')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Charm 3 -->
        <div class="form-group">
            <label for="charm_3">Charm 3 (Nama)</label>
            <input type="text" id="charm_3" name="charm_3" value="{{ old('charm_3', $customProduct->charm_3) }}">
        </div>

        <div class="form-group">
            <label for="charm_3_image">Foto Charm 3</label>
            @if($customProduct->charm_3_image)
                <div style="margin-bottom:10px;">
                    <img src="{{ asset('storage/products/' . $customProduct->charm_3_image) }}" alt="Charm 3" style="width:80px;height:80px;object-fit:cover;border-radius:4px;">
                </div>
            @endif
            <input type="file" id="charm_3_image" name="charm_3_image" accept="image/*">
            @error('charm_3_image')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="image">Gambar Produk</label>
            @if($customProduct->image)
                <div class="current-image">
                    <img src="{{ asset('storage/products/' . $customProduct->image) }}" alt="{{ $customProduct->name }}">
                    <p>Gambar saat ini</p>
                </div>
            @endif
            <input type="file" id="image" name="image" accept="image/*">
            <small>Kosongkan jika tidak ingin mengubah gambar</small>
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
.custom-product-edit-section {
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

.form-group small {
    font-size: 12px;
    color: #666;
    margin-top: 4px;
}

.current-image {
    margin-bottom: 16px;
}

.current-image img {
    max-width: 150px;
    border-radius: 4px;
    margin-bottom: 8px;
}

.current-image p {
    margin: 0;
    font-size: 12px;
    color: #666;
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
