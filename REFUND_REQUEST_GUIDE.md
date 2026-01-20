# 🔄 Sistem Refund Request

## 📋 Penjelasan Sistem

Sistem refund request yang baru memungkinkan customer untuk mengajukan permintaan refund dengan alasan terperinci, yang kemudian akan ditinjau oleh admin sebelum disetujui.

---

## 🔄 Flow Refund Request

```
┌──────────────────────────────────────────────────────────────┐
│ STATUS PESANAN: confirmed, dikemas, atau dikirim             │
│ ↓ Customer klik tombol "⚠️ Request Refund"                   │
└──────────────────────────────────────────────────────────────┘
        ↓
┌──────────────────────────────────────────────────────────────┐
│ MODAL FORM REFUND REQUEST                                    │
│ - Input alasan refund (min 10 karakter)                      │
│ - Klik tombol "✓ Kirim Request"                             │
│ - Request masuk ke database dengan status "pending"           │
└──────────────────────────────────────────────────────────────┘
        ↓
┌──────────────────────────────────────────────────────────────┐
│ ADMIN MENERIMA NOTIFIKASI                                    │
│ - Cek di: /admin/refunds                                     │
│ - Lihat detail refund request                                │
│ - Pilih: Setujui atau Tolak                                 │
└──────────────────────────────────────────────────────────────┘
        ↓
┌──────────────────────────────────────────────────────────────┐
│ JIKA ADMIN SETUJUI (APPROVE)                                 │
│ - Status refund request: "approved"                          │
│ - Status pesanan: "cancelled"                                │
│ - Customer dapat klik "💬 Request Refund via WhatsApp"       │
│ - Hubungi admin untuk proses refund dana                     │
└──────────────────────────────────────────────────────────────┘
        ↓
┌──────────────────────────────────────────────────────────────┐
│ JIKA ADMIN TOLAK (REJECT)                                    │
│ - Status refund request: "rejected"                          │
│ - Pesanan tetap di status sebelumnya                         │
│ - Customer dapat lihat alasan penolakan admin                │
└──────────────────────────────────────────────────────────────┘
```

---

## 👥 Untuk Customer

### Cara Mengajukan Request Refund

**Tempat Akses:**
- Menu: Pesanan Saya → `/orders`
- Status pesanan: Confirmed, Dikemas, atau Dikirim

**Langkah-langkah:**

1. **Buka halaman "Pesanan Saya"**
   - Klik dropdown nama user di kanan atas
   - Pilih "Pesanan Saya"
   - Atau langsung ke: `http://127.0.0.1:8000/orders`

2. **Cari pesanan yang ingin di-refund**
   - Perhatikan status: Confirmed, Dikemas, atau Dikirim
   - Klik tombol **"⚠️ Request Refund"** (warna merah)

3. **Isi form refund request**
   - Jelaskan alasan refund (minimal 10 karakter, maksimal 500)
   - Contoh alasan:
     - "Produk tidak sesuai dengan deskripsi"
     - "Barang rusak saat pengiriman"
     - "Berubah pikiran, ingin membatalkan pesanan"
   - Klik **"✓ Kirim Request"**

4. **Tunggu persetujuan admin**
   - Status pesanan masih menampilkan: Confirmed, Dikemas, atau Dikirim
   - Admin akan meninjau dalam **1x24 jam**
   - Anda akan melihat perubahan status setelah admin memberikan keputusan

5. **Setelah Admin Setujui**
   - Status pesanan berubah menjadi: **"Dibatalkan"**
   - Tombol berubah menjadi: **"💬 Request Refund"** (hijau)
   - Klik untuk langsung chat dengan admin via WhatsApp
   - Admin akan memproses refund dana

### Apa yang Admin Lihat?

Admin dapat melihat:
- Alasan lengkap dari customer
- Order ID dan detail pesanan
- Info customer (nama, email, nomor telepon)
- Total nilai pesanan

---

## 👨‍💼 Untuk Admin

### Lokasi Dashboard

**URL:** `http://127.0.0.1:8000/admin/refunds`

**Atau melalui menu:**
- Login sebagai admin
- Dashboard → Refund (menu baru)

### Tab Status

1. **⏳ Pending** - Request yang belum diproses
2. **✓ Approved** - Request yang sudah disetujui
3. **✕ Rejected** - Request yang ditolak
4. **📊 Semua** - Semua refund request

### Cara Meninjau Refund Request

**1. Lihat List Refund Requests**
   - Tampilkan tab "Pending" untuk melihat request menunggu
   - Informasi yang ditampilkan:
     - Order ID
     - Nama customer
     - Alasan (preview 50 karakter)
     - Status
     - Tanggal request

**2. Klik "Lihat Detail"**
   - Buka halaman detail refund request
   - Lihat informasi lengkap:
     - Customer info (nama, email, nomor telepon)
     - Total pesanan dan jumlah item
     - Alasan refund lengkap
     - Daftar produk yang dipesan

**3. Buat Keputusan: Setujui atau Tolak**

### Menyetujui Request Refund

**Tombol:** "✓ Setujui Request" (hijau)

**Proses:**
1. Klik tombol "✓ Setujui Request"
2. Modal akan muncul
3. (Opsional) Tambahkan catatan untuk customer
4. Klik **"✓ Setujui"**
5. Sistem otomatis:
   - Status refund request → "approved"
   - Status pesanan → "cancelled"
   - Customer bisa segera request refund via WhatsApp

### Menolak Request Refund

**Tombol:** "✕ Tolak Request" (merah)

**Proses:**
1. Klik tombol "✕ Tolak Request"
2. Modal akan muncul
3. **Wajib** isi alasan penolakan (min 5 karakter)
4. Klik **"✕ Tolak"**
5. Sistem:
   - Status refund request → "rejected"
   - Pesanan tetap di status lama
   - Customer bisa lihat alasan penolakan

---

## 📊 Status Pesanan di Halaman Customer

| Status | Tombol | Aksi |
|--------|--------|------|
| **Pending** | 💳 Bayar Sekarang | Redirect ke halaman pembayaran |
| **Confirmed** | ⚠️ Request Refund | Buka modal form refund request |
| **Dikemas** | ⚠️ Request Refund | Buka modal form refund request |
| **Dikirim** | 🚚 Lacak Paket | Tracking pengiriman |
| **Selesai** | ✓ Pesanan Selesai | (disabled) |
| **Cancelled** | 💬 Request Refund | Chat ke admin via WhatsApp |

---

## 🗄️ Database

### Tabel: refund_requests

```sql
- id: integer (primary key)
- user_id: foreign key (users)
- order_id: string (foreign key dari payments.order_id)
- reason: text (alasan refund dari customer)
- status: enum ['pending', 'approved', 'rejected']
- admin_note: text (catatan dari admin, nullable)
- approved_at: timestamp (waktu keputusan admin, nullable)
- approved_by: foreign key (users, nullable)
- created_at: timestamp
- updated_at: timestamp
```

---

## 📁 File yang Terlibat

### Backend
- `app/Models/RefundRequest.php` - Model refund request
- `app/Http/Controllers/PaymentController.php` - Method `requestRefund()`
- `app/Http/Controllers/AdminRefundController.php` - Controller refund admin
- `database/migrations/2026_01_13_202830_create_refund_requests_table.php` - Migration

### Frontend (Customer)
- `resources/views/orders.blade.php` - Halaman pesanan + modal refund form

### Frontend (Admin)
- `resources/views/admin/refunds/index.blade.php` - List refund requests
- `resources/views/admin/refunds/show.blade.php` - Detail & proses refund request

### Routes
- `routes/web.php`:
  - `POST /refund/request/{orderId}` - Customer request refund
  - `GET /admin/refunds` - List refund requests
  - `GET /admin/refunds/{id}` - Detail refund request
  - `POST /admin/refunds/{id}/approve` - Approve refund
  - `POST /admin/refunds/{id}/reject` - Reject refund

---

## ✅ Keuntungan Sistem Baru

1. **Lebih Terstruktur**
   - Customer harus memberikan alasan yang jelas
   - Admin bisa membuat keputusan berdasarkan informasi lengkap

2. **Tracking & Audit**
   - Semua refund request tercatat di database
   - Admin bisa melihat history keputusan
   - Lebih mudah untuk follow-up

3. **Professional**
   - Proses yang jelas dan transparan
   - Customer merasa didengarkan
   - Admin bisa membuat kebijakan refund yang konsisten

4. **Scalable**
   - Jika order banyak, admin bisa membuat batasan/policy refund
   - Bisa menambah field lain di masa depan (e.g., evidence/foto)

---

## 🔐 Validasi & Security

1. **Customer hanya bisa request untuk order miliknya**
   - Check: `where('user_id', Auth::id())`

2. **Prevent duplicate requests**
   - Check: Tidak boleh ada request pending untuk order yang sama

3. **Admin only access**
   - Middleware: `['auth', 'admin']`

4. **CSRF Protection**
   - Semua form request protected dengan CSRF token

---

## 📝 Contoh Penggunaan

### Customer Request Refund
```
1. Klik "⚠️ Request Refund" pada pesanan BTHC-1768328314
2. Modal terbuka
3. Input alasan: "Produk tidak sesuai warna, ingin menukar"
4. Klik "✓ Kirim Request"
5. Alert: "Request refund berhasil dikirim. Admin akan meninjau dalam waktu 1x24 jam."
6. Halaman refresh, status tetap "Confirmed"
```

### Admin Approve Request
```
1. Buka /admin/refunds
2. Lihat tab "Pending"
3. Klik "Lihat Detail" untuk BTHC-1768328314
4. Baca alasan: "Produk tidak sesuai warna, ingin menukar"
5. Klik "✓ Setujui Request"
6. Modal: Input catatan (opsional): "OK, barang bisa ditukar"
7. Klik "✓ Setujui"
8. Alert: "Refund request disetujui. Status pesanan diubah menjadi Dibatalkan"
```

### Customer Lihat Status Berubah
```
1. Refresh halaman /orders
2. Status pesanan: "✕ Dibatalkan"
3. Tombol berubah: "💬 Request Refund" (hijau)
4. Klik tombol, langsung buka WhatsApp ke admin
5. Chat dengan admin tentang process refund
```

---

## 🐛 Troubleshooting

### Error: "Request ini sudah mengajukan refund request"
**Penyebab:** Customer sudah pernah mengajukan request pending untuk order yang sama
**Solusi:** 
- Tunggu admin meninjau
- Atau hubungi admin untuk membatalkan request lama

### Button "Request Refund" tidak muncul
**Penyebab:** Status pesanan bukan confirmed/dikemas/dikirim
**Solusi:** Tunggu hingga pesanan mencapai status tersebut atau hubungi admin

### Admin tidak bisa lihat refund requests
**Penyebab:** User tidak punya role admin
**Solusi:** Login dengan akun admin atau hubungi admin untuk set role

---

**Sistem Terakhir Diupdate:** 14 Januari 2026
**Status:** ✅ AKTIF DAN SIAP DIGUNAKAN
