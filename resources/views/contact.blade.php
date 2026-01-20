@extends('layouts.app')

@section('content')
<style>
    .contact-page {
        padding: 60px 120px;
        min-height: 100vh;
        background-color: #f3eee3;
    }

    .information-label {
        color: #FF4A4A;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 20px;
    }

    .page-title {
        font-size: 48px;
        font-weight: 700;
        color: #111111;
        margin-bottom: 24px;
    }

    .contact-description {
        font-size: 16px;
        color: #666666;
        line-height: 1.6;
        margin-bottom: 40px;
        max-width: 600px;
    }

    .contact-content {
        display: flex;
        gap: 80px;
    }

    .contact-info {
        flex: 1;
    }

    .location-title {
        font-size: 24px;
        font-weight: 600;
        color: #111111;
        margin-bottom: 16px;
    }

    .address {
        font-size: 16px;
        color: #666666;
        line-height: 1.6;
    }

    .contact-form {
        flex: 1;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #111111;
    }

    textarea.form-control {
        min-height: 150px;
        resize: vertical;
    }

    .send-message-btn {
        background-color: #111111;
        color: #ffffff;
        padding: 16px 32px;
        border: none;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .send-message-btn:hover {
        background-color: #333333;
    }

    @media (max-width: 1200px) {
        .contact-page {
            padding: 40px 60px;
        }
    }

    @media (max-width: 768px) {
        .contact-page {
            padding: 30px 20px;
        }

        .contact-content {
            flex-direction: column;
            gap: 40px;
        }

        .page-title {
            font-size: 36px;
        }
    }
</style>

<div class="contact-page">
    <p class="information-label">INFORMATION</p>
    <h1 class="page-title">Contact Us</h1>
    <p class="contact-description">
        Terhubung dengan kami untuk segala pertanyaan seputar produk dan layanan BetterHope Collection.
    </p>

    <div class="contact-content">
        <div class="contact-info">
            <h2 class="location-title">Bandung</h2>
            <p class="address">
                Jl. Sariasih No.54, Sarijadi, Kecamatan Sukasari, Kota Bandung, Jawa Barat 40151.
            </p>
        </div>

        <div class="contact-form">
            <form action="{{ route('contact.store') }}" method="POST">
                @csrf
                @if(session('success'))
                    <div style="padding:10px;background:#e6ffed;border:1px solid #b7f0c9;margin-bottom:12px">{{ session('success') }}</div>
                @endif
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="Name" required>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="phone" placeholder="Phone (optional)">
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="message" placeholder="Message" required></textarea>
                </div>
                <button type="submit" class="send-message-btn">SEND MESSAGE</button>
            </form>
        </div>
    </div>
</div>
@endsection
