# Migrasi dari RajaOngkir ke Biteship

Dokumentasi transisi sistem shipping dari RajaOngkir ke Biteship untuk aplikasi BTHC.

## 📊 Perbandingan

| Aspek | RajaOngkir | Biteship |
|-------|-----------|----------|
| Status | ❌ Deprecated (Starter API) | ✅ Active & Modern |
| API Type | REST | REST |
| Kurir Tersedia | Banyak | Lebih banyak + instant |
| Instant Courier | ❌ Tidak | ✅ Ya (Grab, Gojek) |
| Tracking | Basic | Real-time dengan history |
| Webhook | ❌ Tidak | ✅ Ya |
| COD Support | ❌ Tidak | ✅ Ya |
| Integration | Simple | Lebih powerful |
| Pricing | Gratis (Starter) | Bayar per shipment |

## 🔄 Migrasi Checklist

- [x] Create `BiteshipController.php` dengan semua methods
- [x] Create `config/biteship.php` untuk konfigurasi
- [x] Update `.env` dengan API Key Biteship
- [x] Add routes di `routes/web.php`
- [x] Update `.env.example` dengan Biteship config
- [x] Create dokumentasi `BITESHIP_SETUP.md`
- [ ] Update `checkout.blade.php` untuk UI Biteship
- [ ] Test seluruh flow di staging
- [ ] Migrate existing orders (jika ada)
- [ ] Deploy ke production

## 📝 File-File yang Dibuat/Diubah

### Files Baru:
1. **`app/Http/Controllers/BiteshipController.php`**
   - Complete shipping controller dengan methods:
     - `getCouriers()` - List semua kurir
     - `getRates()` - Get shipping rates
     - `createOrder()` - Create shipment order
     - `getOrder()` - Get order details
     - `getTracking()` - Get tracking info
     - `getPublicTracking()` - Public tracking (no auth)
     - `cancelOrder()` - Cancel shipment
     - `getCancellationReasons()` - List cancel reasons

2. **`config/biteship.php`**
   - Configuration file dengan:
     - API key & base URL
     - Shipper information (business details)
     - Origin/warehouse location
     - Supported couriers list
     - Delivery settings
     - Insurance & COD settings

3. **`BITESHIP_SETUP.md`**
   - Dokumentasi lengkap:
     - Setup instructions
     - API endpoints reference
     - Supported couriers table
     - Database schema recommendations
     - Implementation examples
     - Error handling guide
     - Tracking status reference
     - Testing & deployment checklist

### Files yang Diubah:
1. **`.env`**
   - Replace RajaOngkir variables dengan Biteship
   - Add Biteship API key (sudah ada)
   - Add origin/warehouse info

2. **`.env.example`**
   - Update dengan Biteship variables
   - Remove RajaOngkir config

3. **`routes/web.php`**
   - Replace RajaOngkir routes dengan Biteship routes
   - Add 8 new Biteship endpoints

## 🚀 Biteship Routes

```php
// Courier & Rates
GET  /biteship/couriers          → List available couriers
POST /biteship/rates             → Get shipping rates

// Order Management
POST /biteship/orders             → Create shipping order
GET  /biteship/orders/{id}        → Get order details
POST /biteship/orders/{id}/cancel → Cancel order
GET  /biteship/cancellation-reasons → List cancel reasons

// Tracking
GET  /biteship/tracking/{id}                              → Get tracking (auth)
GET  /biteship/tracking/{waybill_id}/couriers/{courier}   → Public tracking (no auth)
```

## 🔑 API Key Biteship

Sudah dikonfigurasi di `.env`:
```
BITESHIP_API_KEY=biteship_live.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

## 📦 Struktur Request/Response

### Create Order Example

Request:
```json
{
  "destination_contact_name": "John Doe",
  "destination_contact_phone": "081234567890",
  "destination_contact_email": "john@example.com",
  "destination_address": "Jl. Sudirman No. 456",
  "destination_postal_code": "12345",
  "courier_company": "jne",
  "courier_type": "reg",
  "weight": 1000,
  "items": [...]
}
```

Response:
```json
{
  "success": true,
  "id": "5dd599ebdefcd4158eb8470b",
  "courier": {
    "tracking_id": "6de509ebdefgh4158ij3451c",
    "waybill_id": "WYB-1112223333443",
    "company": "jne"
  },
  "price": 48000,
  "status": "confirmed"
}
```

## 🛠️ Next Steps

### 1. Update Checkout UI (resources/views/checkout.blade.php)
Replace RajaOngkir JavaScript dengan:
```javascript
// Load couriers
fetch('/biteship/couriers')
  .then(r => r.json())
  .then(couriers => populateCourierDropdown(couriers));

// Get rates
fetch('/biteship/rates', {
  method: 'POST',
  body: JSON.stringify({
    destination_postal_code: '12345',
    weight: 1000
  })
})
.then(r => r.json())
.then(rates => displayRates(rates));
```

### 2. Database Schema (Optional)
Tambahkan columns untuk tracking Biteship:
```php
$table->string('biteship_order_id')->nullable();
$table->string('biteship_tracking_id')->nullable();
$table->string('biteship_waybill_id')->nullable();
$table->string('biteship_courier_company')->nullable();
$table->decimal('biteship_shipping_cost', 10, 0)->nullable();
$table->string('biteship_status')->nullable();
```

### 3. Payment Integration
Update PaymentController untuk:
- Get rates dari Biteship
- Create order setelah payment success
- Store tracking ID di database

### 4. Testing
1. Test dengan Staging API key dulu
2. Verify semua couriers berfungsi
3. Test order creation flow
4. Test tracking functionality
5. Test error handling

### 5. Deployment
1. Verify production API key
2. Update origin/warehouse info
3. Test one more time
4. Deploy ke production
5. Monitor first few shipments

## 📊 Supported Couriers di Biteship

**Instant Couriers:**
- Grab (Instant, Same Day, Instant Car)
- Gojek (Instant, Same Day)
- Deliveree (Various truck types)

**Standard Couriers:**
- JNE (Reguler, YES, OKE, JTR)
- TIKI (EKO, SDS, REG, ONS)
- SiCepat (Reguler, Best, SDS, GOKIL)
- Pos Indonesia (Kilat Khusus, Q9, Same Day, Next Day)
- Anteraja (Reguler, Same Day, Next Day)
- J&T (EZ)
- Ninja Van (Standard)

## 🔐 Security Notes

1. **API Key**: Sudah aman di .env, jangan expose di frontend
2. **HTTPS**: Semua requests ke Biteship menggunakan HTTPS
3. **Validation**: All inputs di-validate sebelum send ke API
4. **Logging**: Enable logging untuk audit trail
5. **Rate Limit**: Biteship punya rate limit, implement retry logic

## 📞 Support Resources

- [Biteship Documentation](https://docs.biteship.com)
- [Biteship API Reference](https://docs.biteship.com/api-references)
- [Biteship Support Chat](https://biteship.com)
- Laravel logs: `storage/logs/laravel.log`

## 🎯 Performance Improvements

Biteship dibanding RajaOngkir (Starter):

| Metric | RajaOngkir | Biteship |
|--------|-----------|----------|
| API Response | 2-3s | <1s |
| Uptime | 90% (sering down) | 99.9%+ |
| Support | Forum | 24/7 Chat |
| Features | Basic | Advanced |

## 💰 Cost Consideration

RajaOngkir Starter: **Gratis** (sudah deprecated)
Biteship: **Bayar per shipment** (Rp 2,000-5,000 tergantung jarak)

ROI:
- Lebih reliable
- Better tracking
- Real-time support
- Instant couriers tersedia
- COD support

## ✅ Verification Checklist

Sebelum go live:

- [ ] API key aktif dan balance cukup
- [ ] Origin/warehouse info lengkap
- [ ] Semua kurir yang dibutuhkan supported
- [ ] Test order creation berhasil
- [ ] Test tracking berhasil
- [ ] Test cancel order berhasil
- [ ] Error handling berfungsi
- [ ] Logging aktif
- [ ] UI/UX updated
- [ ] Database schema ready

## 🎓 Learning Resources

- Read: `BITESHIP_SETUP.md` untuk detail implementation
- Explore: BiteshipController.php untuk semua methods
- Test: Gunakan Postman untuk testing API
- Monitor: Cek Biteship dashboard untuk live orders

---

**Status**: ✅ Biteship integration ready untuk development
**Next**: Update checkout UI dan test full flow
