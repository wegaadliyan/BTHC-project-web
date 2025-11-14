@extends('layouts.admin')

@section('content')
<div class="payments-section">
    <h1>Payment Details</h1>

    <table class="payments-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Nama Produk</th>
                <th>Email</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->date }}</td>
                <td>{{ $payment->user_name }}</td>
                <td>{{ $payment->product_name }}</td>
                <td>{{ $payment->email }}</td>
                <td>Rp {{ number_format($payment->price, 0, ',', '.') }}</td>
                <td>{{ $payment->quantity }}</td>
                <td>Rp {{ number_format($payment->subtotal, 0, ',', '.') }}</td>
                <td>
                    <button class="confirm-btn">Konfirmasi</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
.payments-section {
    padding: 20px;
}

.payments-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    margin-top: 20px;
    overflow: hidden;
}

.payments-table th,
.payments-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.payments-table th {
    background-color: #F5F1EB;
    font-weight: 600;
}

.confirm-btn {
    padding: 5px 15px;
    background-color: #E6DFD5;
    border: none;
    border-radius: 4px;
    color: #333;
    cursor: pointer;
}

.confirm-btn:hover {
    background-color: #D6CFB5;
}
</style>
@endsection