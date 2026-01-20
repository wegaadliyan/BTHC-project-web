@extends('layouts.admin')

@section('content')
<div class="products-section">
    <div class="section-header">
        <h1>Produk</h1>
        <a href="{{ route('admin.products.create') }}" class="add-btn">Tambah Produk</a>
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
                    <button class="edit-btn" onclick="openEditModal({{ $product->id }}, '{{ addslashes($product->product_code) }}', '{{ addslashes($product->name) }}', '{{ $product->price }}', '{{ $product->weight }}', '{{ addslashes($product->description) }}')">Edit</button>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Edit Produk -->
    <div id="editModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.15);z-index:1000;align-items:center;justify-content:center;">
        <div style="background:#fff;padding:40px 32px 32px 32px;border-radius:28px;max-width:500px;width:100%;margin:80px auto 0 auto;position:relative;">
            <button onclick="closeEditModal()" style="position:absolute;top:24px;right:32px;background:none;border:none;font-size:2rem;color:#888;cursor:pointer;">&times;</button>
            <h2 style="font-size:2rem;font-weight:700;margin-bottom:32px;">Edit Produk</h2>
            <form id="editProductForm" method="POST" enctype="multipart/form-data" onsubmit="return submitEditForm(event)">
                @csrf
                @method('PUT')
                <div style="display:grid;grid-template-columns:140px 1fr;row-gap:24px;column-gap:24px;align-items:center;">
                    <label style="font-size:1.1rem;font-weight:600;">Kode Produk</label>
                    <input type="text" id="editProductCode" name="product_code" style="font-size:1rem;padding:10px 14px;border:1px solid #ccc;border-radius:8px;" required>

                    <label style="font-size:1.1rem;font-weight:600;">Nama Produk</label>
                    <input type="text" id="editName" name="name" style="font-size:1rem;padding:10px 14px;border:1px solid #ccc;border-radius:8px;" required>

                    <label style="font-size:1.1rem;font-weight:600;">Harga</label>
                    <input type="number" id="editPrice" name="price" style="font-size:1rem;padding:10px 14px;border:1px solid #ccc;border-radius:8px;" required>

                    <label style="font-size:1.1rem;font-weight:600;">Berat (gram)</label>
                    <input type="number" id="editWeight" name="weight" style="font-size:1rem;padding:10px 14px;border:1px solid #ccc;border-radius:8px;" required placeholder="Misal: 500">

                    <label style="font-size:1.1rem;font-weight:600;">Deskripsi Produk</label>
                    <input type="text" id="editDescription" name="description" style="font-size:1rem;padding:10px 14px;border:1px solid #ccc;border-radius:8px;">

                    <label style="font-size:1.1rem;font-weight:600;">Gambar Produk</label>
                    <input type="file" name="image" style="font-size:1rem;padding:10px 14px;border:1px solid #ccc;border-radius:8px;background:#faf7f2;">
                </div>
                <div style="display:flex;justify-content:flex-end;margin-top:40px;">
                    <button type="submit" style="background:#D2B893;color:#fff;font-size:1.2rem;font-weight:600;padding:10px 36px;border:none;border-radius:10px;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openEditModal(id, productCode, name, price, weight, description) {
        document.getElementById('editModal').style.display = 'flex';
        document.getElementById('editProductCode').value = productCode;
        document.getElementById('editName').value = name;
        document.getElementById('editPrice').value = price;
        document.getElementById('editWeight').value = weight;
        document.getElementById('editDescription').value = description;
        document.getElementById('editProductForm').action = '/admin/products/' + id;
    }
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
    function submitEditForm(e) {
        e.preventDefault();
        var form = document.getElementById('editProductForm');
        form.submit();
        return false;
    }
    </script>
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