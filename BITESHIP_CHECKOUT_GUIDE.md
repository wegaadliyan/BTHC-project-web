# 📦 PANDUAN CHECKOUT BITESHIP

## Penjelasan Sistem

Sistem checkout BTHC menggunakan **Biteship Shipping API** untuk menghitung tarif pengiriman secara real-time. Berikut cara kerjanya:

### ⚙️ FLOW SISTEM

```
┌─────────────────────────────────────────────────────────────────┐
│ 1. PAGE LOAD                                                     │
│    ↓ Kirim request: GET /biteship/couriers                      │
│    ↓ Terima: List 20+ kurir (JNE, TIKI, SiCepat, dsb)           │
│    ↓ Tampilkan di form sebagai pilihan radio button             │
└─────────────────────────────────────────────────────────────────┘
        ↓
┌─────────────────────────────────────────────────────────────────┐
│ 2. USER INPUT DATA PENGIRIMAN                                   │
│    - Nama Lengkap Penerima (required)                            │
│    - No. Telepon Penerima (required)                             │
│    - Email Penerima (optional)                                   │
│    - Kode Pos (required) ⚠️ HARUS 5 DIGIT                       │
│    - Alamat Lengkap (required)                                   │
│    - Catatan Tambahan (optional)                                 │
│    - Berat Barang (required) ⚠️ DALAM GRAM, MIN 100g             │
│    - Nilai Barang (optional)                                     │
│    - Pilih Kurir (required) - Dari list yang sudah loaded        │
└─────────────────────────────────────────────────────────────────┘
        ↓
┌─────────────────────────────────────────────────────────────────┐
│ 3. KLIK "HITUNG BIAYA PENGIRIMAN"                               │
│    ↓ Validasi data lokal (kode pos 5 digit, berat > 100)        │
│    ↓ Kirim POST ke /biteship/rates dengan data:                 │
│      - destination_postal_code (kode pos tujuan)                 │
│      - weight (berat dalam gram)                                 │
│      - courier_code (kode kurir pilihan, misal: "jne")           │
│      - item_value (nilai barang)                                 │
└─────────────────────────────────────────────────────────────────┘
        ↓
┌─────────────────────────────────────────────────────────────────┐
│ 4. SERVER PROSES (BACKEND)                                      │
│    ↓ Controller: getRates() method                               │
│    ↓ Query Biteship API: https://api.biteship.com/v1/rates      │
│    ↓ Hitung tarif berdasarkan:                                  │
│      - Rute (dari origin 12440 ke destination_postal_code)       │
│      - Berat barang                                              │
│      - Kurir yang dipilih                                        │
│    ↓ Return: List pilihan pengiriman dengan harga               │
└─────────────────────────────────────────────────────────────────┘
        ↓
┌─────────────────────────────────────────────────────────────────┐
│ 5. TAMPILKAN PILIHAN PENGIRIMAN                                 │
│    Misal untuk JNE:                                              │
│    ┌─────────────────────────────────┐                          │
│    │ JNE                             │                          │
│    │ Reguler (2-3 days)              │ Rp 12.500               │
│    └─────────────────────────────────┘                          │
│    ┌─────────────────────────────────┐                          │
│    │ JNE                             │                          │
│    │ Express (1 day)                 │ Rp 25.000               │
│    └─────────────────────────────────┘                          │
│    (User klik salah satu)                                        │
└─────────────────────────────────────────────────────────────────┘
        ↓
┌─────────────────────────────────────────────────────────────────┐
│ 6. KLIK PILIHAN PENGIRIMAN                                      │
│    ↓ Update total harga: Subtotal + Biaya Pengiriman            │
│    ↓ Enable button "Lanjutkan ke Pembayaran"                    │
│    ↓ Simpan data pengiriman di memory                           │
└─────────────────────────────────────────────────────────────────┘
        ↓
┌─────────────────────────────────────────────────────────────────┐
│ 7. KLIK "LANJUTKAN KE PEMBAYARAN"                               │
│    ↓ Submit form dengan semua data pengiriman                   │
│    ↓ Redirect ke halaman pembayaran (/pay/{id})                 │
│    ↓ Di payment page, simpan info pengiriman di database        │
└─────────────────────────────────────────────────────────────────┘
```

---

## ⚠️ VALIDASI PENTING

### Kode Pos (Postal Code)
- ❌ **SALAH**: 2000000 (7 digit), 123 (3 digit)
- ✅ **BENAR**: 12345 (5 digit), 50134, 95251

**Contoh kode pos Indonesia:**
- Jakarta: 12345 - 14450
- Bandung: 40111 - 40248
- Surabaya: 60111 - 60299

### Berat (Weight)
- ❌ **SALAH**: 50 (kurang dari 100 gram), 0
- ✅ **BENAR**: 100, 200, 500, 1000

**Format**: SELALU dalam GRAM (gram), bukan kilogram!
- 100g = 100
- 500g = 500
- 1kg = 1000

### Kurir (Courier)
Harus dipilih dari list yang di-load dari Biteship API:
- Gojek (instant, 1-3 hours)
- Grab (instant, 1-3 hours)
- JNE (standard, 2-3 days)
- TIKI (standard, 2-4 days)
- SiCepat (standard, 2-3 days)
- Pos Indonesia (standard, 1-3 days)
- Dan kurir lainnya...

---

## 🔧 Troubleshooting

### Error: "Gagal memuat kurir"
**Penyebab**: API key tidak valid atau koneksi ke Biteship API gagal
**Solusi**: 
- Refresh halaman
- Cek koneksi internet
- Hubungi admin untuk cek API key

### Error: "Kode Pos harus 5 digit"
**Penyebab**: Kode pos yang dimasukkan tidak sesuai format
**Solusi**: Ubah kode pos menjadi 5 digit (contoh: 12345)

### Error: "Berat minimal 100 gram"
**Penyebab**: Berat barang kurang dari 100 gram
**Solusi**: Masukkan berat minimal 100 gram

### Error: "Tidak ada pilihan pengiriman untuk kota ini"
**Penyebab**: Rute pengiriman ke area tersebut tidak tersedia di kurir pilihan
**Solusi**: 
- Coba kurir lain
- Pastikan kode pos tujuan valid

### Error: "Data pengiriman tidak valid"
**Penyebab**: Ada data yang tidak sesuai format
**Solusi**: Periksa kembali:
- Kode pos (5 digit)
- Berat (>= 100 gram)
- Kurir sudah dipilih

---

## 📁 File yang Terlibat

### Frontend (User Interface)
- `resources/views/checkout.blade.php` - Tampilan form checkout dengan JavaScript
- jQuery untuk AJAX requests

### Backend (Server Logic)
- `app/Http/Controllers/BiteshipController.php` - Controller dengan 8 methods:
  - `getCouriers()` - Load list kurir
  - `getRates()` - Hitung tarif pengiriman
  - `createOrder()` - Buat order pengiriman
  - `getOrder()` - Get status order
  - `getTracking()` - Tracking pengiriman
  - `cancelOrder()` - Batalkan order
  - Dan method lainnya...

### Configuration
- `config/biteship.php` - Konfigurasi Biteship
- `.env` - API key dan informasi origin

### Routes
- `routes/web.php` - Endpoint API:
  - `GET /biteship/couriers` - Load kurir
  - `POST /biteship/rates` - Hitung tarif
  - `POST /biteship/orders` - Buat order
  - Dan routes lainnya...

---

## 📞 Kontak & Support

Jika ada masalah atau pertanyaan:
1. Cek bagian Troubleshooting di atas
2. Buka DevTools (F12) → Console tab untuk lihat error message detail
3. Hubungi admin/developer

---

**Dibuat**: 19 December 2025
**Sistem**: Biteship Shipping API Integration
**Status**: ✅ ACTIVE
