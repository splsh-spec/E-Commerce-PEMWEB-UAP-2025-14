@extends('layouts.admin')

@section('content')
<h1>Manajemen User</h1>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Role</th>
        <th>Store</th>
        <th>Aksi</th>
    </tr>

    @foreach($users as $u)
    <tr>
        <td>{{ $u->id }}</td>
        <td>{{ $u->name }}</td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->role }}</td>
        <td>{{ $u->store->name ?? '-' }}</td>
        <td>
            <a href="{{ route('admin.users.edit', $u->id) }}">Edit</a>

            <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button onclick="return confirm('Yakin hapus user?')">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection