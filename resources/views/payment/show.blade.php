@extends('layouts.app')

@section('content')
<div class="container" style="display: flex; justify-content: center; align-items: center; height: 80vh; text-align: center;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 16px;">Mempersiapkan Pembayaran...</h1>
        <p style="margin-bottom: 32px; color: #555;">Anda akan dialihkan ke halaman pembayaran Midtrans. Mohon tunggu.</p>
        <div id="snap-container"></div>
        {{-- Tombol ini hanya sebagai fallback jika pop-up tidak muncul --}}
        <button id="pay-button" style="display: none; background-color:#8e44ad; color:white; padding:12px 24px; border:none; border-radius:8px; font-size:1rem; cursor:pointer;">Lanjutkan Pembayaran</button>
    </div>
</div>

{{-- Muat library Midtrans Snap --}}
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        // Ambil snap token dari controller
        var snapToken = "{{ $snapToken }}";

        function triggerPayment() {
            window.snap.pay(snapToken, {
            onSuccess: function(result){
                    /* Implementasi Anda setelah sukses */
                    console.log('SUCCESS', result);
                    window.location.href = '{{ route("checkout.success") }}?order_id=' + result.order_id + '&status_code=' + result.status_code;
                },
                onPending: function(result){
                    /* Implementasi Anda saat pembayaran pending */
                    console.log('PENDING', result);
                    window.location.href = '{{ route("checkout.pending") }}?order_id=' + result.order_id + '&status_code=' + result.status_code;
                },
                onError: function(result){
                    /* Implementasi Anda saat error */
                    console.log('ERROR', result);
                    alert("Pembayaran gagal. " + (result.status_message || ""));
                },
                onClose: function(){
                    /* Implementasi saat pop-up ditutup */
                    console.log('customer closed the popup without finishing the payment');
                    alert('Anda menutup jendela pembayaran sebelum selesai.');
                }
            });
        }

        // Panggil fungsi pembayaran secara otomatis
        triggerPayment();

        // Tampilkan tombol fallback jika ada masalah
        document.getElementById('pay-button').style.display = 'inline-block';
        document.getElementById('pay-button').onclick = triggerPayment;
    });
</script>
@endsection