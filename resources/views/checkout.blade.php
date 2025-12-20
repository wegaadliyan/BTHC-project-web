@extends('layouts.app')

@section('content')
<div class="container" style="max-width:900px;margin:40px auto;">
    <h1 style="font-size:2rem;font-weight:700;margin-bottom:32px;">Checkout</h1>
    <div style="margin-bottom:32px;">
        <h2 style="font-size:1.2rem;font-weight:600;margin-bottom:12px;">Alamat Pengiriman</h2>
        <div style="background:#f3eee3;padding:20px;border-radius:8px;">
            <!-- Biteship Shipping Form -->
            <form id="biteship-form" autocomplete="off">
                <div style="display:flex;gap:12px;margin-bottom:12px;">
                    <input type="text" id="recipient_name" placeholder="Nama Lengkap Penerima" style="flex:1;padding:8px;" required>
                    <input type="text" id="recipient_phone" placeholder="No. Telepon Penerima" style="flex:1;padding:8px;" required>
                </div>
                <div style="display:flex;gap:12px;margin-bottom:12px;">
                    <input type="email" id="recipient_email" placeholder="Email Penerima (Optional)" style="flex:1;padding:8px;">
                </div>

                <!-- Provinsi, Kota, Kecamatan Selection -->
                <div style="display:flex;gap:12px;margin-bottom:12px;">
                    <select id="province" style="flex:1;padding:8px;border:1px solid #ddd;border-radius:4px;" required>
                        <option value="">Pilih Provinsi</option>
                    </select>
                    <select id="city" style="flex:1;padding:8px;border:1px solid #ddd;border-radius:4px;" required disabled>
                        <option value="">Pilih Kota/Kabupaten</option>
                    </select>
                </div>

                <div style="display:flex;gap:12px;margin-bottom:12px;">
                    <select id="district" style="flex:1;padding:8px;border:1px solid #ddd;border-radius:4px;" required disabled>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                    <input type="text" id="postal_code" placeholder="Kode Pos (Auto)" style="flex:1;padding:8px;background:#f5f5f5;" required readonly>
                </div>

                <textarea id="shipping_address" placeholder="Alamat Lengkap Pengiriman" style="width:100%;padding:8px;margin-bottom:12px;min-height:80px;" required></textarea>
                <textarea id="shipping_note" placeholder="Catatan Tambahan (Optional)" style="width:100%;padding:8px;margin-bottom:12px;min-height:60px;"></textarea>
                
                <div style="display:flex;gap:12px;margin-bottom:12px;">
                    <input type="number" id="weight" min="100" placeholder="Berat (gram)" style="flex:1;padding:8px;" value="{{ $totalWeight }}" required>
                    <!-- Hidden field: item_value is auto-populated from cart subtotal -->
                    <input type="hidden" id="item_value" min="0" value="{{ $cartItems->sum('price') }}">
                </div>

                <div style="margin-bottom:12px;">
                    <label style="display:block;margin-bottom:8px;font-weight:600;">Pilih Kurir & Layanan:</label>
                    <div id="courier-options" style="background:white;padding:12px;border-radius:4px;max-height:300px;overflow-y:auto;">
                        <div style="color:#999;text-align:center;padding:20px;">Loading kurir...</div>
                    </div>
                </div>

                <button type="button" id="calculate-btn" class="btn-check" style="padding:10px 24px;background:#e6dfd5;border-radius:4px;cursor:pointer;">Hitung Biaya Pengiriman</button>
            </form>
            <div id="shipping-result" style="margin-top:16px;"></div>
        </div>
    </div>

    <div>
        <h2 style="font-size:1.2rem;font-weight:600;margin-bottom:12px;">Keranjang Anda</h2>
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f3eee3;">
                    <th style="padding:12px 8px;text-align:left;">Produk</th>
                    <th style="padding:12px 8px;text-align:center;">Jumlah</th>
                    <th style="padding:12px 8px;text-align:center;">Berat (g)</th>
                    <th style="padding:12px 8px;text-align:right;">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:12px 8px;">
                        @if($item->custom_product_id)
                            <div>
                                <strong>{{ $item->customProduct->name ?? '-' }}</strong>
                                <div style="font-size:0.95em;color:#555;">
                                    @if($item->color) <span>Warna: {{ $item->color }}</span> @endif
                                    @if($item->size) <span> | Ukuran: {{ $item->size }}</span> @endif
                                    @if($item->charm) <span> | Charm: {{ $item->charm }}</span> @endif
                                </div>
                            </div>
                        @else
                            {{ $item->product->name ?? '-' }}
                        @endif
                    </td>
                    <td style="padding:12px 8px;text-align:center;">{{ $item->quantity }}</td>
                    <td style="padding:12px 8px;text-align:center;">
                        @php
                            $product = $item->product ?? $item->customProduct;
                            $itemWeight = $product && $product->weight ? ($product->weight * $item->quantity) : 0;
                        @endphp
                        {{ $itemWeight }}
                    </td>
                    <td style="padding:12px 8px;text-align:right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top:32px;padding:20px;background:#f9f9f9;border-radius:8px;">
            <div style="margin-bottom:12px;font-size:1.1rem;">
                <strong>Total Berat:</strong> {{ $totalWeight }} gram
            </div>
            <div style="margin-bottom:12px;font-size:1.1rem;">
                <strong>Subtotal:</strong> Rp <span id="subtotal">{{ number_format($cartItems->sum('price'), 0, ',', '.') }}</span>
            </div>
            <div style="margin-bottom:12px;font-size:1.1rem;">
                <strong>Biaya Pengiriman:</strong> Rp <span id="shipping-cost" style="color:#e74c3c;">0</span>
            </div>
            <hr style="border:1px solid #ddd;margin:12px 0;">
            <div style="font-size:1.3rem;font-weight:600;text-align:right;">
                Total: Rp <span id="total-price">{{ number_format($cartItems->sum('price'), 0, ',', '.') }}</span>
            </div>

            <button type="button" id="checkout-btn" class="btn btn-primary" style="width:100%;margin-top:16px;padding:12px;cursor:not-allowed;opacity:0.5;" disabled>
                Pilih Pengiriman Terlebih Dahulu
            </button>
        </div>
    </div>
</div>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
<script>
// ============================================================================
// SISTEM BITESHIP CHECKOUT EXPLANATION
// ============================================================================
// 
// FLOW SISTEM:
// 1. PAGE LOAD → Load semua kurir dari /biteship/couriers
// 2. USER INPUT → User masukkan: nama, telp, alamat, kode pos (5 digit), berat (gram)
//    Nilai barang OTOMATIS diambil dari subtotal keranjang
// 3. USER SELECT COURIER → Pilih kurir dari list yang sudah loaded
// 4. CLICK "HITUNG BIAYA" → Kirim POST ke /biteship/rates dengan:
//    - destination_postal_code (kode pos tujuan, 5 digit)
//    - weight (berat barang dalam gram, min 100)
//    - courier_code (kode kurir yang dipilih, misal: "jne", "tiki", "pos")
//    - item_value (nilai barang dari subtotal, auto-populated)
// 5. SERVER QUERY BITESHIP API → Hitung tarif berdasarkan rute & berat
// 6. SHOW RATES → Tampilkan pilihan pengiriman dengan harga
// 7. USER SELECT RATE → Klik salah satu pilihan pengiriman
// 8. SUBMIT TO PAYMENT → Lanjut ke halaman pembayaran dengan detail pengiriman
//
// IMPORTANT NOTES:
// - Kode Pos HARUS 5 DIGIT (format: 12345)
// - Berat HARUS dalam GRAM dan minimal 100g
// - Kurir harus dipilih sebelum hitung biaya
// - Nilai barang otomatis dari subtotal (tidak perlu input manual)
// ============================================================================

$(document).ready(function() {
    const subtotal = {{ $cartItems->sum('price') }};
    let selectedShipping = null;
    let regionsData = [];

    // Load regions (provinces, cities, districts) on page load
    loadRegions();
    loadCouriers();

    // Load regions data from JSON file
    function loadRegions() {
        $.ajax({
            url: '/wilayah-indonesia.json',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                regionsData = data;
                populateProvinces(data);
            },
            error: function() {
                console.error('Failed to load regions data');
            }
        });
    }

    // Populate provinces dropdown
    function populateProvinces(data) {
        let html = '<option value="">Pilih Provinsi</option>';
        $.each(data, function(i, province) {
            html += `<option value="${province.province_id}">${province.province}</option>`;
        });
        $('#province').html(html);
    }

    // Handle province change
    $('#province').on('change', function() {
        const provinceId = $(this).val();
        if (!provinceId) {
            $('#city').prop('disabled', true).html('<option value="">Pilih Kota/Kabupaten</option>');
            $('#district').prop('disabled', true).html('<option value="">Pilih Kecamatan</option>');
            $('#postal_code').val('');
            return;
        }

        const province = regionsData.find(p => p.province_id === provinceId);
        if (province) {
            let html = '<option value="">Pilih Kota/Kabupaten</option>';
            $.each(province.cities, function(i, city) {
                html += `<option value="${city.city_id}" data-postal="${city.postal_code}">${city.city_name}</option>`;
            });
            $('#city').html(html).prop('disabled', false);
            $('#district').prop('disabled', true).html('<option value="">Pilih Kecamatan</option>');
            $('#postal_code').val('');
        }
    });

    // Handle city change
    $('#city').on('change', function() {
        const provinceId = $('#province').val();
        const cityId = $(this).val();
        if (!cityId) {
            $('#district').prop('disabled', true).html('<option value="">Pilih Kecamatan</option>');
            $('#postal_code').val('');
            return;
        }

        const province = regionsData.find(p => p.province_id === provinceId);
        const city = province.cities.find(c => c.city_id === cityId);
        if (city && city.districts) {
            let html = '<option value="">Pilih Kecamatan</option>';
            $.each(city.districts, function(i, district) {
                html += `<option value="${district.name}" data-postal="${district.postal_code}">${district.name}</option>`;
            });
            $('#district').html(html).prop('disabled', false);
            // Set postal code to city's postal code by default
            $('#postal_code').val(city.postal_code);
        }
    });

    // Handle district change
    $('#district').on('change', function() {
        const districtName = $(this).val();
        if (districtName) {
            const postalCode = $(this).find('option:selected').data('postal');
            if (postalCode) {
                $('#postal_code').val(postalCode);
            }
        }
    });

    function loadCouriers() {
        $.get('/biteship/couriers', function(data) {
            console.log('Couriers loaded:', data);
            
            if (!data || !Array.isArray(data) || data.length === 0) {
                $('#courier-options').html('<div style="color:#999;text-align:center;padding:20px;">Tidak ada kurir tersedia</div>');
                return;
            }

            let html = '';
            // Group by courier name
            let grouped = {};
            $.each(data, function(i, courier) {
                const company = courier.courier_name || 'Unknown';
                if (!grouped[company]) {
                    grouped[company] = [];
                }
                grouped[company].push(courier);
            });

            // Create radio buttons grouped by company
            $.each(grouped, function(company, couriers) {
                html += `<div style="margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #eee;">
                    <strong>${company}</strong>`;
                $.each(couriers, function(i, courier) {
                    const value = courier.courier_code + '|' + courier.courier_service_code;
                    html += `<div style="margin-top:8px;margin-left:12px;">
                        <label style="display:flex;align-items:center;cursor:pointer;">
                            <input type="radio" name="courier" value="${value}" 
                                   data-company="${courier.courier_code}" 
                                   data-service="${courier.courier_service_code}"
                                   style="margin-right:8px;">
                            <span>${courier.courier_service_name} 
                                <small style="color:#999;">(${courier.shipment_duration_range} ${courier.shipment_duration_unit})</small>
                            </span>
                        </label>
                    </div>`;
                });
                html += '</div>';
            });

            $('#courier-options').html(html);
        }).fail(function(xhr) {
            console.error('Failed to load couriers:', xhr);
            $('#courier-options').html('<div style="color:#e74c3c;text-align:center;padding:20px;">Gagal memuat kurir. Silakan refresh halaman.</div>');
        });
    }

    // Calculate shipping cost
    $('#calculate-btn').on('click', function() {
        const province = $('#province').val();
        const city = $('#city').val();
        const district = $('#district').val();
        const postalCode = $('#postal_code').val();
        const weight = $('#weight').val();
        const courierRadio = $('input[name="courier"]:checked');

        if (!province || !city || !district || !postalCode || !weight || !courierRadio.length) {
            alert('Lengkapi semua data: Provinsi, Kota, Kecamatan, Berat, dan Kurir!');
            return;
        }

        // Validate postal code format (5 digits)
        if (!/^\d{5}$/.test(postalCode)) {
            alert('Kode Pos harus 5 digit (contoh: 12345)');
            return;
        }

        // Validate weight (minimum 100 gram)
        if (parseInt(weight) < 100) {
            alert('Berat minimal 100 gram');
            return;
        }

        const courierCode = courierRadio.data('company');

        $('#shipping-result').html('<div style="color:#666;padding:20px;text-align:center;">Menghitung biaya pengiriman...</div>');

        console.log('Sending rates request:', {
            destination_postal_code: postalCode,
            weight: weight,
            courier_code: courierCode
        });

        $.ajax({
            url: '/biteship/rates',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                destination_postal_code: postalCode,
                weight: weight,
                courier_code: courierCode,
                item_value: $('#item_value').val() || 0
            },
            success: function(rates) {
                console.log('Rates success:', rates);
                if (!rates || rates.length === 0) {
                    $('#shipping-result').html('<div style="color:#999;padding:20px;text-align:center;">Tidak ada pilihan pengiriman untuk kota ini</div>');
                    return;
                }

                let html = '<div style="margin-top:12px;">';
                $.each(rates, function(i, rate) {
                    const courier = rate.courier_name || rate.courier_code;
                    const service = rate.courier_service_name || rate.type;
                    const price = rate.price || 0;
                    const etd = rate.shipment_duration_range + ' ' + rate.shipment_duration_unit;

                    html += `<div style="padding:12px;background:#f9f9f9;margin:8px 0;border-left:4px solid #e6dfd5;cursor:pointer;" 
                                 onclick="selectShipping(${price}, '${courier} - ${service}')">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <strong>${courier}</strong><br>
                                <small style="color:#666;">${service}</small><br>
                                <small style="color:#999;">Estimasi: ${etd}</small>
                            </div>
                            <div style="text-align:right;font-weight:600;font-size:1.1rem;">
                                Rp ${new Intl.NumberFormat('id-ID').format(price)}
                            </div>
                        </div>
                    </div>`;
                });
                html += '</div>';

                $('#shipping-result').html(html);
            },
            error: function(xhr, status, error) {
                console.error('Rates error:', xhr);
                let errorMsg = 'Gagal mengambil tarif. ';
                
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg += xhr.responseJSON.error;
                } else if (xhr.status === 400) {
                    errorMsg += 'Data pengiriman tidak valid. Periksa kode pos (5 digit) dan berat (minimal 100g).';
                } else if (xhr.status === 404) {
                    errorMsg += 'Rute pengiriman ke area ini tidak tersedia.';
                } else {
                    errorMsg += error || 'Error tidak diketahui';
                }
                
                $('#shipping-result').html('<div style="color:#e74c3c;padding:20px;border:1px solid #e74c3c;border-radius:4px;">' + errorMsg + '</div>');
            }
        });
    });

    // Select shipping option
    window.selectShipping = function(cost, label) {
        selectedShipping = {cost: cost, label: label};
        const total = subtotal + cost;

        $('#shipping-cost').text(new Intl.NumberFormat('id-ID').format(cost));
        $('#total-price').text(new Intl.NumberFormat('id-ID').format(total));
        
        $('#shipping-result').html(`
            <div style="padding:12px;background:#d4edda;border:1px solid #c3e6cb;border-radius:4px;color:#155724;">
                ✓ <strong>${label}</strong> dipilih
                <br>Biaya: Rp ${new Intl.NumberFormat('id-ID').format(cost)}
            </div>
        `);

        // Enable checkout button
        $('#checkout-btn').prop('disabled', false).css('opacity', '1').css('cursor', 'pointer').text('Lanjutkan ke Pembayaran');
    };

    // Checkout
    $('#checkout-btn').on('click', function() {
        if (!selectedShipping) {
            alert('Pilih pengiriman terlebih dahulu!');
            return;
        }

        const recipientName = $('#recipient_name').val();
        const recipientPhone = $('#recipient_phone').val();
        const address = $('#shipping_address').val();
        const postalCode = $('#postal_code').val();

        if (!recipientName || !recipientPhone || !address || !postalCode) {
            alert('Lengkapi data penerima dan alamat pengiriman!');
            return;
        }

        // Store checkout data in session/form for next step
        const courierRadio = $('input[name="courier"]:checked');
        const courierData = courierRadio.val().split('|');

        // Create hidden form to submit to payment
        const form = $('<form>', {
            method: 'POST',
            action: '{{ route("pay", $cart->id ?? $order->id ?? 1) }}'
        });

        form.append($('<input>', {type: 'hidden', name: '_token', value: $('meta[name="csrf-token"]').attr('content')}));
        form.append($('<input>', {type: 'hidden', name: 'recipient_name', value: recipientName}));
        form.append($('<input>', {type: 'hidden', name: 'recipient_phone', value: recipientPhone}));
        form.append($('<input>', {type: 'hidden', name: 'recipient_email', value: $('#recipient_email').val()}));
        form.append($('<input>', {type: 'hidden', name: 'province', value: $('#province').val()}));
        form.append($('<input>', {type: 'hidden', name: 'city', value: $('#city').val()}));
        form.append($('<input>', {type: 'hidden', name: 'district', value: $('#district').val()}));
        form.append($('<input>', {type: 'hidden', name: 'shipping_address', value: address}));
        form.append($('<input>', {type: 'hidden', name: 'postal_code', value: postalCode}));
        form.append($('<input>', {type: 'hidden', name: 'shipping_note', value: $('#shipping_note').val()}));
        form.append($('<input>', {type: 'hidden', name: 'weight', value: $('#weight').val()}));
        form.append($('<input>', {type: 'hidden', name: 'courier_company', value: courierData[0]}));
        form.append($('<input>', {type: 'hidden', name: 'courier_type', value: courierData[1]}));
        form.append($('<input>', {type: 'hidden', name: 'shipping_cost', value: selectedShipping.cost}));

        $('body').append(form);
        form.submit();
    });
});
</script>
