@extends('layouts.admin')

@section('content')
<style>
    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .form-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
        color: #333;
    }

    .btn-back {
        background-color: #6C757D;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-back:hover {
        background-color: #5A6268;
        color: white;
    }

    .card {
        border: none;
        border-radius: 8px;
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .card-header {
        border-bottom: 1px solid #eee;
        padding: 20px;
        background: #f9f9f9;
    }

    .card-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .required {
        color: #DC3545;
    }

    input[type="file"],
    input[type="text"],
    input[type="url"],
    textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.3s;
    }

    input[type="file"]:focus,
    input[type="text"]:focus,
    input[type="url"]:focus,
    textarea:focus {
        outline: none;
        border-color: #111111;
        box-shadow: 0 0 0 3px rgba(17,17,17,0.1);
    }

    .form-text {
        display: block;
        margin-top: 6px;
        font-size: 12px;
        color: #666;
    }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .checkbox-wrapper label {
        margin: 0;
        font-weight: 500;
        cursor: pointer;
    }

    .image-preview {
        margin-top: 15px;
    }

    .image-preview img {
        max-width: 300px;
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background-color: #111111;
        color: white;
    }

    .btn-primary:hover {
        background-color: #333333;
        color: white;
    }

    .btn-secondary {
        background-color: #6C757D;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5A6268;
        color: white;
    }

    .error-message {
        color: #DC3545;
        font-size: 12px;
        margin-top: 6px;
        display: block;
    }

    .form-group.has-error input,
    .form-group.has-error textarea {
        border-color: #DC3545;
    }
</style>

<div class="form-header">
    <h1>Tambah Banner Baru</h1>
    <a href="{{ route('admin.banners.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>


<div class="card">
    <div class="card-header">
        <h5 style="margin: 0;">Form Tambah Banner</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                <label for="image">Gambar Banner <span class="required">*</span></label>
                <input type="file" id="image" name="image" accept="image/*" required>
                <span class="form-text">Format: JPG, JPEG, PNG, GIF. Maksimal 5MB</span>
                @error('image')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                <div id="imagePreview" class="image-preview"></div>
            </div>

            <div class="form-group">
                <label for="alt_text">Alt Text (Deskripsi Gambar)</label>
                <input type="text" id="alt_text" name="alt_text" placeholder="Contoh: Banner Koleksi Gelang" value="{{ old('alt_text') }}">
                @error('alt_text')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="link">Link (URL Tujuan)</label>
                <input type="url" id="link" name="link" placeholder="Contoh: https://example.com" value="{{ old('link') }}">
                <span class="form-text">Biarkan kosong jika tidak ingin menambahkan link</span>
                @error('link')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <div class="checkbox-wrapper">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                    <label for="is_active">Aktifkan Banner</label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Banner
                </button>
                <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail';
                img.style.maxWidth = '300px';
                preview.appendChild(img);
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection
