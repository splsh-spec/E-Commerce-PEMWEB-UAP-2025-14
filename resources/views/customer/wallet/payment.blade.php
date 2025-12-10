@extends('layouts.app')

@section('content')
<h1>Instruksi Pembayaran</h1>

<p>Silakan transfer ke Virtual Account berikut:</p>

<h2>{{ $topup->va_number }}</h2>

<p>Nominal: <strong>Rp {{ number_format($topup->amount) }}</strong></p>

<form method="POST" action="{{ route('wallet.confirm') }}">
    @csrf
    <input type="hidden" name="topup_id" value="{{ $topup->id }}">
    <button type="submit">Saya sudah membayar</button>
</form>
@endsection