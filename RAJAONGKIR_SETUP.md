# Setup RajaOngkir Integration

Panduan lengkap untuk mengonfigurasi integrasi RajaOngkir pada aplikasi BTHC.

## ⚠️ PENTING: Perubahan RajaOngkir API (Per Desember 2024)

RajaOngkir Starter API endpoint sudah **DEPRECATED** dan tidak lagi aktif. Namun, aplikasi ini sudah dilengkapi dengan **fallback mock data** sehingga fitur checkout tetap berfungsi tanpa API key.

### Status Saat Ini:
- ❌ **RajaOngkir Starter API**: Sudah tidak aktif (endpoint 503/410)
- ✅ **Fallback Mock Data**: Aktif dan berfungsi untuk development & testing
- ✅ **Checkout Feature**: Tetap berjalan dengan mock data

### Pilihan Solusi:

#### 1. **Gunakan Mock Data (Recommended untuk Development)** ⭐
Aplikasi sudah siap dengan fallback mock data otomatis. Anda dapat langsung menggunakan fitur checkout tanpa API key.

**Keuntungan:**
- Tidak perlu API key
- Testing & development dapat langsung berjalan
- Sudah ada data untuk 32 provinsi Indonesia
- Simulasi biaya pengiriman sudah tersedia

#### 2. **Migrasi ke Platform Baru RajaOngkir** (Untuk Production)
Jika ingin menggunakan API asli:

1. Kunjungi [https://collaborator.komerce.id](https://collaborator.komerce.id)
2. Daftar akun sebagai Seller/Partner
3. Lakukan renewal/upgrade package API
4. Dapatkan API Key dari dashboard baru mereka
5. Update `.env` dengan API key baru

#### 3. **Alternatif: Gunakan Shipper API Lain**
Jika ingin migrasi ke platform lain:
- **Shipper.id** - Platform logistics Indonesia terkini
- **OnLogistic** - Multi-carrier API
- **Biteship** - Shipping aggregator terintegrasi

## Quick Start (Development)

### Setup Minimal:

Aplikasi sudah siap. Tidak ada konfigurasi yang diperlukan untuk mulai development.

Jika ingin, edit `.env` (opsional):
```env
# Opsional - hanya jika punya API key baru dari RajaOngkir
RAJAONGKIR_API_KEY=
RAJAONGKIR_BASE_URL=https://api.rajaongkir.com/starter
RAJAONGKIR_ORIGIN_CITY_ID=154
```

### Test Langsung:

1. Akses halaman checkout: `http://localhost:8000/checkout`
2. Dropdown provinsi akan terisi dari mock data
3. Pilih provinsi, akan muncul kota-kota
4. Pilih kurir dan berat paket
5. Klik "Hitung Ongkos Kirim" untuk lihat estimasi

## Mock Data yang Tersedia

### Provinsi (32 Provinsi)
Semua provinsi di Indonesia sudah tersedia di mock data, dari Aceh hingga Sumatera Utara.

### Kota (Contoh untuk Jakarta - Province ID 6)
```
- Jakarta Pusat (154)
- Jakarta Selatan (155)
- Jakarta Timur (156)
- Jakarta Utara (157)
- Jakarta Barat (158)
```

### Provinsi Lain yang Memiliki Mock Data:
- **Jawa Barat (9)**: Bandung (73), Bogor (71), Bekasi (72)
- **Jawa Tengah (10)**: Semarang (168), Solo (165)

Untuk provinsi lain, aplikasi akan menampilkan array kosong (tidak ada kota) hingga API key baru dikonfigurasi.

### Biaya Pengiriman (Mock)
Untuk semua kombinasi provinsi-kota-kurir, aplikasi menampilkan:
- **Reguler**: Rp 50.000 (estimasi 2-3 hari)
- **Express**: Rp 75.000 (estimasi 1-2 hari)

## Cara Kerja Aplikasi

### Alur Checkout:
1. User membuka halaman checkout
2. Pilih provinsi → Aplikasi load kota via `/provinces`
3. Pilih kota → Aplikasi load kota via `/cities/{province_id}`
4. Pilih kurir (JNE/TIKI/POS) dan masukkan berat paket
5. Klik "Hitung Ongkos Kirim" → Aplikasi call `/check-ongkir`
6. Estimasi biaya ditampilkan
7. Proceed to payment dengan total = subtotal + ongkos kirim

### Fallback Logic:
```
Jika API Key ada dan valid:
  ├─ Coba hubungi RajaOngkir API
  └─ Jika gagal, gunakan mock data
  
Jika API Key kosong atau tidak valid:
  └─ Gunakan mock data langsung
```

## Struktur Routes

Aplikasi memiliki 3 routes untuk RajaOngkir:

```php
Route::get('/provinces', [RajaOngkirController::class, 'getProvinces']);
Route::get('/cities/{province_id}', [RajaOngkirController::class, 'getCities']);
Route::post('/check-ongkir', [RajaOngkirController::class, 'checkOngkir']);
```

## Response Format

### GET /provinces
```json
[
  {"province_id": "1", "province": "Aceh"},
  {"province_id": "2", "province": "Bangka Belitung"},
  ...
]
```

### GET /cities/{province_id}
```json
[
  {"city_id": "154", "province_id": "6", "city_name": "Jakarta Pusat", "type": "Kota"},
  {"city_id": "155", "province_id": "6", "city_name": "Jakarta Selatan", "type": "Kota"},
  ...
]
```

### POST /check-ongkir
Request:
```json
{
  "destination": "154",
  "weight": 1000,
  "courier": "jne"
}
```

Response:
```json
[
  {
    "service": "Reguler",
    "description": "Reguler",
    "cost": [
      {
        "value": 50000,
        "etd": "2-3",
        "note": ""
      }
    ]
  },
  {
    "service": "Express",
    "description": "Express",
    "cost": [
      {
        "value": 75000,
        "etd": "1-2",
        "note": ""
      }
    ]
  }
]
```

## Upgrade ke API Real

Saat siap untuk production dengan API asli:

### Step 1: Dapatkan API Key Baru
- Kunjungi https://collaborator.komerce.id
- Upgrade package
- Dapatkan API key

### Step 2: Update .env
```env
RAJAONGKIR_API_KEY=your_new_api_key
RAJAONGKIR_BASE_URL=https://api.komerce.id/v1  # Sesuaikan endpoint baru mereka
RAJAONGKIR_ORIGIN_CITY_ID=154  # Jakarta Pusat (atau kota asal Anda)
```

### Step 3: Update Controller (Jika Endpoint Berbeda)
Jika endpoint API baru berbeda, edit `app/Http/Controllers/RajaOngkirController.php` dan sesuaikan:
- Endpoint URL
- Header authentication
- Response format parsing

### Step 4: Testing
- Clear config cache: `php artisan config:cache`
- Test dengan postman atau browser
- Pastikan API key valid

## Troubleshooting

### Dropdown tidak muncul
- **Solusi**: Buka browser DevTools (F12), cek Network tab untuk error pada request `/provinces`
- Pastikan Laravel server running: `php artisan serve`

### "Error loading provinces"
- **Penyebab**: Request timeout atau API error
- **Solusi**: Periksa laravel.log di `storage/logs/`
- Application akan fallback ke mock data otomatis

### Ongkir tidak dihitung
- **Cek**: Pastikan semua field terisi (provinsi, kota, kurir, berat)
- **Cek**: Browser console untuk error AJAX
- **Solusi**: Refresh halaman dan coba lagi

### City tidak muncul untuk provinsi tertentu
- **Penyebab**: Mock data hanya ada untuk Jakarta, Jawa Barat, Jawa Tengah
- **Solusi**: Upgrade ke API key baru untuk semua kota Indonesia

## Referensi

- [RajaOngkir Baru (Komerce)](https://collaborator.komerce.id)
- [Shipper.id API](https://shipper.id)
- [Biteship API](https://biteship.com)
- [Laravel HTTP Client](https://laravel.com/docs/http-client)

## File yang Relevan

- Controller: `app/Http/Controllers/RajaOngkirController.php`
- View: `resources/views/checkout.blade.php`
- Routes: `routes/web.php`
- Config: `config/rajaongkir.php`
- Env: `.env` (optional)
