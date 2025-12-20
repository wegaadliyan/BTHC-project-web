@extends('layouts.app')

@section('content')
<style>
    .password-card {
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
    .password-card h2 {
        font-size: 1.7rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 32px;
        text-align: center;
    }
    .password-form {
        width: 100%;
    }
    .password-form .form-group {
        margin-bottom: 22px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .password-form label {
        font-weight: 600;
        color: #444;
        margin-bottom: 7px;
    }
    .password-form input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 15px;
        font-family: inherit;
    }
    .password-form input:focus {
        outline: none;
        border-color: #D2B893;
        box-shadow: 0 0 0 2px rgba(210, 184, 147, 0.1);
    }
    .btn-password {
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
    .btn-password:hover {
        background-color: #d6cfb5;
    }
</style>

<div class="password-card">
    <h2>Ubah Password</h2>
    <form method="POST" action="{{ route('profile.update-password') }}" class="password-form">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="current_password">Password Saat Ini</label>
            <input type="password" name="current_password" id="current_password" required>
        </div>
        <div class="form-group">
            <label for="password">Password Baru</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>
        <button type="submit" class="btn-password">Simpan Password</button>
    </form>
    <a href="{{ route('profile.edit') }}" class="btn-back">&larr; Kembali ke Profil</a>
</div>
<style>
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
@endsection