# Biteship Integration Guide

Panduan lengkap untuk mengintegrasikan Biteship API ke dalam aplikasi BTHC.

## 📱 Overview

Biteship adalah platform shipping aggregator yang menghubungkan bisnis Anda dengan berbagai kurir pengiriman di Indonesia dan kawasan. Dengan Biteship, Anda dapat:

- ✅ Mengakses multiple kurir (JNE, TIKI, SiCepat, Grab, Gojek, dll)
- ✅ Mendapatkan rates/tarif otomatis
- ✅ Membuat shipment order langsung dari aplikasi
- ✅ Real-time tracking pengiriman
- ✅ Support Cash on Delivery (COD)
- ✅ Insurance untuk barang berharga

## 🔑 Konfigurasi Awal

### 1. Dapatkan Biteship API Key

1. Kunjungi [https://biteship.com](https://biteship.com)
2. Daftar akun merchant/seller
3. Verifikasi email dan data bisnis
4. Pergi ke Dashboard → Settings/Integrations
5. Copy API Key (Live atau Staging)

### 2. Update .env File

Edit file `.env` di root project:

```env
# Biteship Configuration
BITESHIP_API_KEY=biteship_live.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
BITESHIP_BASE_URL=https://api.biteship.com/v1

# Biteship Shipper Info (Nama bisnis Anda)
BITESHIP_SHIPPER_NAME=BTHC Store
BITESHIP_SHIPPER_PHONE=081234567890
BITESHIP_SHIPPER_EMAIL=hello@bthc.com
BITESHIP_SHIPPER_ORG=Better Hope Collection

# Biteship Origin (Lokasi pickup/warehouse Anda)
BITESHIP_ORIGIN_NAME=BTHC Warehouse
BITESHIP_ORIGIN_PHONE=081234567890
BITESHIP_ORIGIN_EMAIL=warehouse@bthc.com
BITESHIP_ORIGIN_ADDRESS=Jl. Merdeka No. 123, Jakarta Pusat
BITESHIP_ORIGIN_POSTAL_CODE=12150
BITESHIP_ORIGIN_NOTE=Pintu masuk samping
# Untuk instant courier (Grab, Gojek), gunakan koordinat
BITESHIP_ORIGIN_LATITUDE=-6.2253114
BITESHIP_ORIGIN_LONGITUDE=106.7993735
```

### 3. Clear Config Cache

```bash
php artisan config:cache
```

## 📋 API Endpoints

Aplikasi telah dikonfigurasi dengan routes berikut:

### Courier Management
```
GET /biteship/couriers
```
Mendapatkan daftar kurir yang tersedia.

Response:
```json
[
  {
    "courier_name": "JNE",
    "courier_code": "jne",
    "courier_service_name": "Reguler",
    "courier_service_code": "reg",
    "shipment_duration_range": "1-3",
    "shipment_duration_unit": "days",
    ...
  }
]
```

### Get Shipping Rates
```
POST /biteship/rates
```

Request Body:
```json
{
  "destination_postal_code": "12345",
  "weight": 1000,
  "courier_code": "jne",
  "value": 500000
}
```

Response:
```json
[
  {
    "courier_name": "JNE",
    "courier_code": "jne",
    "courier_service_name": "Reguler",
    "courier_service_code": "reg",
    "price": 50000,
    "estimated_delivery": "1-3",
    ...
  }
]
```

### Create Shipping Order
```
POST /biteship/orders
```

Request Body:
```json
{
  "destination_contact_name": "John Doe",
  "destination_contact_phone": "081234567890",
  "destination_contact_email": "john@example.com",
  "destination_address": "Jl. Sudirman No. 456",
  "destination_postal_code": "12345",
  "destination_note": "Near the mall",
  "courier_company": "jne",
  "courier_type": "reg",
  "weight": 1000,
  "items": [
    {
      "name": "T-Shirt",
      "description": "Black T-Shirt Size L",
      "value": 165000,
      "quantity": 1,
      "weight": 200,
      "height": 10,
      "width": 10,
      "length": 10
    }
  ],
  "reference_id": "ORD-123456"
}
```

Response:
```json
{
  "success": true,
  "message": "Order successfully created",
  "object": "order",
  "id": "5dd599ebdefcd4158eb8470b",
  "courier": {
    "tracking_id": "6de509ebdefgh4158ij3451c",
    "waybill_id": "WYB-1112223333443",
    "company": "jne",
    ...
  },
  "price": 48000,
  "status": "confirmed"
}
```

### Get Order Details
```
GET /biteship/orders/{order_id}
```

### Track Shipment
```
GET /biteship/tracking/{tracking_id}
```

Response includes:
- Current status (confirmed, allocated, picking_up, picked, dropping_off, delivered, etc)
- Full tracking history
- Driver details
- Estimated delivery time

### Public Tracking (No Auth)
```
GET /biteship/tracking/{waybill_id}/couriers/{courier_code}
```

### Cancel Order
```
POST /biteship/orders/{order_id}/cancel
```

Request Body:
```json
{
  "reason_code": "change_courier",
  "reason": "Ingin mengganti kurir"
}
```

## 🚚 Supported Couriers

| Code | Courier | Services |
|------|---------|----------|
| jne | JNE | Reguler, YES (Express), OKE |
| tiki | TIKI | EKO, SDS, REG, ONS |
| sicepat | SiCepat | Reguler, Best, SDS, GOKIL |
| pos | Pos Indonesia | Kilat Khusus, Q9, Same Day, Next Day |
| grab | Grab | Instant, Same Day, Instant Car |
| gojek | Gojek | Instant, Same Day |
| anteraja | Anteraja | Reguler, Same Day, Next Day |
| jnt | J&T | EZ |
| ninja | Ninja Van | Standard |
| deliveree | Deliveree | Various (Truck, Van, Bike, dll) |

## 💾 Database Setup

Untuk menyimpan order Biteship di database, tambahkan columns ke payment table:

```php
// Migration
Schema::table('payments', function (Blueprint $table) {
    $table->string('biteship_order_id')->nullable();
    $table->string('biteship_tracking_id')->nullable();
    $table->string('biteship_waybill_id')->nullable();
    $table->string('biteship_courier_company')->nullable();
    $table->decimal('biteship_shipping_cost', 10, 0)->nullable();
    $table->string('biteship_status')->nullable();
});
```

## 📦 Implementasi di Checkout

### JavaScript untuk Handle Biteship

```javascript
// Get couriers
async function loadCouriers() {
  const response = await fetch('/biteship/couriers');
  const couriers = await response.json();
  // Populate dropdown
}

// Get rates
async function getRates(postalCode, weight) {
  const response = await fetch('/biteship/rates', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
    },
    body: JSON.stringify({
      destination_postal_code: postalCode,
      weight: weight
    })
  });
  return await response.json();
}

// Create order
async function createShippingOrder(orderData) {
  const response = await fetch('/biteship/orders', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
    },
    body: JSON.stringify(orderData)
  });
  return await response.json();
}
```

## 🔄 Alur Pengiriman (Checkout hingga Deliver)

1. **User di Checkout Page**
   - Pilih alamat pengiriman
   - Sistem mengambil kurir tersedia via `/biteship/couriers`

2. **Get Shipping Rates**
   - User pilih kurir + berat paket
   - Sistem call `/biteship/rates`
   - Tampilkan pilihan pengiriman dengan harga

3. **Create Shipping Order**
   - User confirm order
   - Sistem call `/biteship/orders` untuk create shipment
   - Terima tracking ID & waybill ID
   - Simpan ke database

4. **Payment**
   - Customer lakukan pembayaran
   - Update order status di database

5. **Tracking**
   - Customer bisa track di order page
   - Sistem call `/biteship/tracking/{tracking_id}`
   - Update status realtime

6. **Delivery**
   - Kurir pickup barang dari origin
   - Deliver ke customer
   - Customer konfirmasi terima barang

## 🛡️ Error Handling

Biteship memiliki beberapa error codes:

```
40002060 - Reference ID sudah pernah digunakan
40003001 - Failed to retrieve tracking
40003002 - Courier tracking not available
40003003 - Waybill not found
```

### Best Practices:

1. **Use Unique Reference ID**: Setiap order harus punya reference_id unik
2. **Handle Rate Limits**: Biteship memiliki rate limit, implement retry logic
3. **Log Everything**: Catat semua API calls untuk debugging
4. **Test Mode**: Gunakan Staging API key dulu untuk testing
5. **Balance Check**: Pastikan balance Biteship cukup

## 🔐 Security

1. **Never expose API Key** - Selalu gunakan di backend, jangan di frontend
2. **Use HTTPS** - Semua request harus HTTPS
3. **Validate Input** - Validate semua user input sebelum send ke API
4. **Rate Limiting** - Implement rate limiting untuk API calls
5. **Logging** - Log semua transaction untuk audit trail

## 📊 Tracking Status

| Status | Meaning |
|--------|---------|
| confirmed | Order confirmed, mencari driver |
| allocated | Driver dialokasikan, menunggu pickup |
| picking_up | Driver dalam perjalanan pickup |
| picked | Barang sudah diambil |
| dropping_off | Barang dalam perjalanan ke customer |
| delivered | Barang sudah diterima |
| returned | Barang dikembalikan |
| cancelled | Order dibatalkan |
| rejected | Order ditolak |
| on_hold | Sedang on hold |

## 🧪 Testing

### Test dengan Postman

```
Authorization: Bearer {BITESHIP_API_KEY}
Content-Type: application/json

POST https://api.biteship.com/v1/orders
{
  "origin_contact_name": "Test",
  "origin_contact_phone": "081234567890",
  "origin_address": "Test Address",
  "origin_postal_code": "12440",
  "destination_contact_name": "Test Customer",
  "destination_contact_phone": "081234567890",
  "destination_address": "Test Destination",
  "destination_postal_code": "12950",
  "courier_company": "jne",
  "courier_type": "reg",
  "items": [{"name": "Test", "weight": 100, "value": 10000}]
}
```

### Test di Staging

Gunakan API Key dari Staging mode di Biteship dashboard untuk testing tanpa charge.

## 📚 Referensi

- [Biteship Docs](https://docs.biteship.com)
- [Biteship API Reference](https://docs.biteship.com/api-references)
- [List of Couriers](https://docs.biteship.com/api-references/couriers)
- [Create Order Details](https://docs.biteship.com/api-references/orders)
- [Tracking Guide](https://docs.biteship.com/api-references/trackings)

## 🚀 Deployment Checklist

- [ ] API Key dari production Biteship
- [ ] Update .env dengan production key
- [ ] Test shipping flow end-to-end
- [ ] Setup webhook untuk order status updates (optional)
- [ ] Monitor Biteship balance regularly
- [ ] Setup alert untuk low balance
- [ ] Document shipper/origin info
- [ ] Train team tentang shipping workflow
- [ ] Setup customer support untuk tracking inquiries

## 💬 Support

Untuk bantuan:
1. Cek Biteship documentation
2. Contact Biteship support team
3. Check Laravel logs di `storage/logs/laravel.log`
4. Enable Biteship webhook untuk real-time updates
