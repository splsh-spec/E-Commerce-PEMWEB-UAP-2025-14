@extends('layouts.app')

@section('content')
<h1>Riwayat Transaksi</h1>

@if($transactions->isEmpty())
    <p>Belum ada transaksi.</p>
@else
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>Kode</th>
        <th>Tanggal</th>
        <th>Status</th>
        <th>Total</th>
        <th>Aksi</th>
    </tr>

    @foreach($transactions as $t)
    <tr>
        <td>{{ $t->code }}</td>
        <td>{{ $t->created_at->format('d M Y H:i') }}</td>
        <td>{{ $t->payment_status ?? 'pending' }}</td>
        <td>Rp {{ number_format($t->grand_total) }}</td>
        <td>
            <a href="{{ route('transaction.show', $t->id) }}">Detail</a>
        </td>
    </tr>
    @endforeach
</table>
@endif

@endsection