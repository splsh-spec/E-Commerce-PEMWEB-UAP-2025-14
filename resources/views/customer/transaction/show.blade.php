@extends('layouts.app')

@section('content')
<h1>Detail Transaksi</h1>

<p><strong>Kode Transaksi:</strong> {{ $transaction->code }}</p>
<p><strong>Status Pembayaran:</strong> {{ $transaction->payment_status ?? 'pending' }}</p>

<p><strong>Alamat:</strong> 
   {{ $transaction->address }}, 
   {{ $transaction->city }},
   {{ $transaction->postal_code }}
</p>

<p><strong>Pengiriman:</strong> 
    {{ strtoupper($transaction->shipping) }} - {{ $transaction->shipping_type }}
</p>

<p><strong>Ongkir:</strong> 
    Rp {{ number_format($transaction->shipping_cost) }}
</p>

<p><strong>Total Bayar:</strong> 
    Rp {{ number_format($transaction->grand_total) }}
</p>

<h3>Produk yang Dibeli</h3>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>Produk</th>
        <th>Qty</th>
        <th>Subtotal</th>
    </tr>

    @foreach($transaction->transactionDetails as $d)
    <tr>
        <td>{{ $d->product->name }}</td>
        <td>{{ $d->qty }}</td>
        <td>Rp {{ number_format($d->subtotal) }}</td>
    </tr>
    @endforeach
</table>

<a href="{{ route('transaction.index') }}">← Kembali ke Riwayat</a>
@endsection