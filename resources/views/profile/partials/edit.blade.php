<h2>Profil Saya</h2>
<hr style="margin-bottom: 32px;">
<div style="display: flex; gap: 32px; align-items: flex-start;">
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" style="flex: 1; display: flex; gap: 32px; align-items: flex-start;">
        @csrf
        @method('PUT')
        <div style="flex: 1;">
            <div class="form-group" style="margin-bottom: 24px; display: grid; grid-template-columns: 150px 1fr; align-items: center; gap: 20px;">
                <label for="name">Nama</label>
                <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
            </div>
            <div class="form-group" style="margin-bottom: 24px; display: grid; grid-template-columns: 150px 1fr; align-items: center; gap: 20px;">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
            </div>
            <div class="form-group" style="margin-bottom: 24px; display: grid; grid-template-columns: 150px 1fr; align-items: center; gap: 20px;">
                <label for="phone">Nomor Telepon</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}">
            </div>
            <div class="form-actions" style="margin-top: 40px; grid-column: 2;">
                <button type="submit" class="btn-submit" style="background-color: #D2B893; color: #fff; padding: 10px 28px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer;">Simpan</button>
            </div>
        </div>
    </form>
</div>
