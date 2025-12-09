@extends('layouts.app')

@section('content')
<h1>Manajemen Store</h1>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Nama Toko</th>
        <th>Pemilik</th>
        <th>Verifikasi</th>
        <th>Aksi</th>
    </tr>

    @foreach($stores as $s)
    <tr>
        <td>{{ $s->id }}</td>
        <td>{{ $s->name }}</td>
        <td>{{ $s->user->name }}</td>
        <td>{{ $s->is_verified ? 'Terverifikasi' : 'Belum' }}</td>

        <td>
            <a href="{{ route('admin.stores.edit', $s->id) }}">Edit</a>

            <form action="{{ route('admin.stores.delete', $s->id) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button onclick="return confirm('Yakin hapus store?')">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection