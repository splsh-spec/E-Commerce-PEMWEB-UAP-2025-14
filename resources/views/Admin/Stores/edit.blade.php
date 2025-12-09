@extends('layouts.app')

@section('content')
<h1>Edit Store</h1>

<form method="POST" action="{{ route('admin.stores.update', $store->id) }}">
    @csrf
    @method('PUT')

    <label>Nama Toko:</label><br>
    <input type="text" name="name" value="{{ $store->name }}"><br><br>

    <label>Deskripsi:</label><br>
    <textarea name="description">{{ $store->description }}</textarea><br><br>

    <label>Alamat:</label><br>
    <textarea name="address">{{ $store->address }}</textarea><br><br>

    <label>Verifikasi:</label><br>
    <select name="is_verified">
        <option value="0" {{ $store->is_verified ? '' : 'selected' }}>Belum Diverifikasi</option>
        <option value="1" {{ $store->is_verified ? 'selected' : '' }}>Terverifikasi</option>
    </select><br><br>

    <button type="submit">Simpan</button>
</form>
@endsection