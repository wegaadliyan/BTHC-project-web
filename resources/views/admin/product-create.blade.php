@extends('layouts.admin')

@section('content')
<div style="max-width:900px;margin:40px auto 0 auto;background:#fff;padding:0;">
    <h1 style="font-size:2.5rem;font-weight:700;margin-bottom:40px;">Tambah Produk</h1>
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display:grid;grid-template-columns:220px 1fr;row-gap:32px;column-gap:32px;align-items:center;">
            <label style="font-size:1.35rem;font-weight:700;">ID Produk</label>
            <input type="text" name="product_code" style="font-size:1.1rem;padding:12px 16px;border:1px solid #ccc;border-radius:8px;" required value="{{ old('product_code') }}">
            <label style="font-size:1.35rem;font-weight:700;">Nama Produk</label>
            <input type="text" name="name" style="font-size:1.1rem;padding:12px 16px;border:1px solid #ccc;border-radius:8px;" required value="{{ old('name') }}">

            <label style="font-size:1.35rem;font-weight:700;">Harga Produk</label>
            <input type="number" name="price" style="font-size:1.1rem;padding:12px 16px;border:1px solid #ccc;border-radius:8px;" required value="{{ old('price') }}">

            <label style="font-size:1.35rem;font-weight:700;">Berat Produk (gram)</label>
            <input type="number" name="weight" style="font-size:1.1rem;padding:12px 16px;border:1px solid #ccc;border-radius:8px;" required placeholder="Misal: 500" value="{{ old('weight') }}">

            <label style="font-size:1.35rem;font-weight:700;">Deskripsi Produk</label>
            <input type="text" name="description" style="font-size:1.1rem;padding:12px 16px;border:1px solid #ccc;border-radius:8px;" value="{{ old('description') }}">

            <label style="font-size:1.35rem;font-weight:700;">Gambar Produk</label>
            <input type="file" name="image" style="font-size:1.1rem;padding:12px 16px;border:1px solid #ccc;border-radius:8px;background:#faf7f2;">
        </div>
        <div style="display:flex;justify-content:flex-end;margin-top:60px;">
            <button type="submit" style="background:#D2B893;color:#222;font-size:2rem;font-weight:600;padding:12px 48px;border:none;border-radius:12px;">Tambah</button>
        </div>
    </form>
</div>
@endsection
