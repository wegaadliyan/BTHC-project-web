@extends('layouts.admin')

@section('content')
<div class="profile-section">
    <h1>Profil</h1>

    <div class="profile-content">
        <div class="profile-header">
            <div class="profile-avatar"></div>
            <h2>Nama Admin</h2>
        </div>

        <form class="profile-form">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" value="{{ auth()->user()->name }}" class="form-control">
            </div>

            <div class="form-group">
                <label>No. Telepon</label>
                <input type="text" value="{{ auth()->user()->phone }}" class="form-control">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" value="{{ auth()->user()->email }}" class="form-control">
            </div>

            <div class="form-group">
                <label>Foto Profil</label>
                <div class="file-upload">
                    <button type="button" class="upload-btn">Pilih File</button>
                    <span class="file-info">Tidak ada file yang dipilih</span>
                </div>
            </div>

            <button type="submit" class="save-btn">Simpan</button>
        </form>
    </div>
</div>

<style>
.profile-section {
    padding: 20px;
}

.profile-content {
    background: white;
    border-radius: 8px;
    padding: 30px;
    max-width: 600px;
    margin: 20px auto;
}

.profile-header {
    text-align: center;
    margin-bottom: 30px;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background-color: #E6DFD5;
    margin: 0 auto 15px;
}

.profile-form .form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #666;
}

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.file-upload {
    display: flex;
    align-items: center;
    gap: 10px;
}

.upload-btn {
    padding: 8px 16px;
    background-color: #F5F1EB;
    border: none;
    border-radius: 4px;
    color: #333;
    cursor: pointer;
}

.file-info {
    color: #666;
    font-size: 14px;
}

.save-btn {
    width: 100%;
    padding: 10px;
    background-color: #E6DFD5;
    border: none;
    border-radius: 4px;
    color: #333;
    font-size: 16px;
    cursor: pointer;
    margin-top: 20px;
}

.save-btn:hover {
    background-color: #D6CFB5;
}
</style>
@endsection