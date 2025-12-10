@extends('layouts.app')

@section('content')
<h1>Top Up Saldo</h1>

@if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('wallet.topup.make') }}">
    @csrf

    <label>Nominal Top Up</label>
    <input type="number" name="amount" min="1000" required>

    <button type="submit">Buat VA</button>
</form>
@endsection