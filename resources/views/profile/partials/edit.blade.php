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
            <div class="form-group" style="margin-bottom: 24px; display: grid; grid-template-columns: 150px 1fr; align-items: center; gap: 20px;">
                <label>Jenis Kelamin</label>
                <div class="radio-group" style="display: flex; gap: 30px;">
                    <div class="radio-option" style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" id="female" name="gender" value="Perempuan" {{ (Auth::user()->gender ?? '') == 'Perempuan' ? 'checked' : '' }}>
                        <label for="female">Perempuan</label>
                    </div>
                    <div class="radio-option" style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" id="male" name="gender" value="Laki-Laki" {{ (Auth::user()->gender ?? '') == 'Laki-Laki' ? 'checked' : '' }}>
                        <label for="male">Laki-Laki</label>
                    </div>
                </div>
            </div>
            <div class="form-actions" style="margin-top: 40px; grid-column: 2;">
                <button type="submit" class="btn-submit" style="background-color: #D2B893; color: #fff; padding: 10px 28px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer;">Simpan</button>
            </div>
        </div>
        <div style="display: flex; flex-direction: column; align-items: center; gap: 16px;">
            <div style="width: 120px; height: 120px; background: #e0e0e0; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                @if(Auth::user()->profile_image)
                    <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="8" r="4" stroke="black" stroke-width="2" fill="none"/>
                        <path d="M4 20c0-4 4-7 8-7s8 3 8 7" stroke="black" stroke-width="2" fill="none"/>
                    </svg>
                @endif
            </div>
            <input type="file" id="profile_image" name="profile_image" accept="image/*" style="display:none;">
            <label for="profile_image" style="background: #fff; border-radius: 8px; padding: 10px 24px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: none;">Pilih Gambar</label>
        </div>
    </form>
</div>
