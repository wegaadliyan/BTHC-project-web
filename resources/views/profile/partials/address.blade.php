<h2>Alamat</h2>

<form action="{{ route('profile.update-address') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="full_name">Nama Lengkap</label>
        <input type="text" id="full_name" name="full_name" value="{{ old('full_name', Auth::user()->full_name ?? '') }}">
    </div>

    <div class="form-group">
        <label for="phone">Nomor Telepon</label>
        <input type="tel" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}">
    </div>

    <div class="form-group">
        <label for="address">Provinsi,Kota,Kecamatan,Kode Pos</label>
        <input type="text" id="address" name="address" value="{{ old('address', Auth::user()->address ?? '') }}">
    </div>

    <div class="form-group">
        <label for="street">Nama Jalan,Gedung,No.Rumah</label>
        <input type="text" id="street" name="street" value="{{ old('street', Auth::user()->street ?? '') }}">
    </div>

    <div class="form-group">
        <label for="detail_address">Detail Lainnya (Blok,Patokan)</label>
        <textarea id="detail_address" name="detail_address">{{ old('detail_address', Auth::user()->detail_address ?? '') }}</textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn-submit">Simpan</button>
        <a href="{{ route('profile.address') }}" class="btn-cancel">Batal</a>
    </div>
</form>
