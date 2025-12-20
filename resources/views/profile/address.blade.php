@extends('layouts.app')

@section('content')
<style>
    .address-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        max-width: 480px;
        margin: 48px auto;
        padding: 40px 32px 32px 32px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .address-card h2 {
        font-size: 1.7rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 32px;
        text-align: center;
    }
    .address-form {
        width: 100%;
    }
    .address-form .form-group {
        margin-bottom: 22px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .address-form label {
        font-weight: 600;
        color: #444;
        margin-bottom: 7px;
    }
    .address-form input,
    .address-form textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 15px;
        font-family: inherit;
    }
    .address-form input:focus,
    .address-form textarea:focus {
        outline: none;
        border-color: #D2B893;
        box-shadow: 0 0 0 2px rgba(210, 184, 147, 0.1);
    }
    .btn-address {
        width: 100%;
        padding: 12px;
        background-color: #e6dfd5;
        border: none;
        border-radius: 4px;
        color: #222;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 12px;
        transition: background 0.2s;
    }
    .btn-address:hover {
        background-color: #d6cfb5;
    }
    .btn-back {
        display: block;
        width: 100%;
        margin-top: 18px;
        padding: 10px;
        background: #f3eee3;
        color: #222;
        border-radius: 4px;
        text-align: center;
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        transition: background 0.2s;
        border: none;
    }
    .btn-back:hover {
        background: #e6dfd5;
    }
</style>

<div class="address-card">
    <h2>Ubah Alamat</h2>
    <form method="POST" action="{{ route('profile.update-address') }}" class="address-form">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="address">Alamat</label>
            <input type="text" name="address" id="address" value="{{ old('address', auth()->user()->address) }}">
        </div>
        <div class="form-group">
            <label for="street">Jalan</label>
            <input type="text" name="street" id="street" value="{{ old('street', auth()->user()->street) }}">
        </div>
        <div class="form-group">
            <label for="detail_address">Detail Alamat</label>
            <textarea name="detail_address" id="detail_address">{{ old('detail_address', auth()->user()->detail_address) }}</textarea>
        </div>
        <button type="submit" class="btn-address">Simpan Alamat</button>
    </form>
    <a href="{{ route('profile.edit') }}" class="btn-back">&larr; Kembali ke Profil</a>
</div>
@endsection