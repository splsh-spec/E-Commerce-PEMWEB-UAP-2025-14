@extends('layouts.admin')

@section('content')
<h1>Verifikasi Toko</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Nama Toko</th>
        <th>Pemilik</th>
        <th>Aksi</th>
    </tr>

    @forelse($stores as $store)
        <tr>
            <td>{{ $store->id }}</td>
            <td>{{ $store->name }}</td>
            <td>{{ $store->user->name }}</td>
            <td>
                <form action="{{ route('admin.verification.approve', $store->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit">Verifikasi</button>
                </form>

                <form action="{{ route('admin.verification.reject', $store->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" onclick="return confirm('Tolak pendaftaran toko?')">Tolak</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4">Tidak ada toko yang perlu diverifikasi.</td>
        </tr>
    @endforelse
</table>
@endsection