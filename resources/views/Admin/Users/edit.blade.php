@extends('layouts.admin')

@section('content')
<h1>Edit User</h1>

<form method="POST" action="{{ route('admin.users.update', $user->id) }}">
    @csrf
    @method('PUT')

    <label>Nama:</label><br>
    <input type="text" name="name" value="{{ $user->name }}"><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="{{ $user->email }}"><br><br>

    <label>Role:</label><br>
    <select name="role">
        <option value="member" {{ $user->role == 'member' ? 'selected' : '' }}>Member</option>
        <option value="seller" {{ $user->role == 'seller' ? 'selected' : '' }}>Seller</option>
        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
    </select><br><br>

    <button type="submit">Simpan</button>
</form>
@endsection