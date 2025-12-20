<h2>Ubah Password</h2>

<form method="POST" action="{{ route('profile.update-password') }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="current_password">Password Lama</label>
        <input type="password" id="current_password" name="current_password" required>
    </div>

    <div class="form-group">
        <label for="password">Password baru</label>
        <input type="password" id="password" name="password" required>
    </div>

    <div class="form-group">
        <label for="password_confirmation">Konfirmasi Password Baru</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn-submit">Simpan</button>
        <a href="{{ route('profile.password') }}" class="btn-cancel">Batal</a>
    </div>
</form>
