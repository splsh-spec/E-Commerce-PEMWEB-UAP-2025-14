@extends('layouts.seller')

@section('title', 'Produk Saya')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Produk Saya</h3>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary">+ Tambah Produk</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stock</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        @foreach($products as $p)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $p->name }}</td>
            <td>{{ $p->productCategory->name }}</td>
            <td>Rp{{ number_format($p->price,0,',','.') }}</td>
            <td>{{ $p->stock }}</td>
            <td>
                <a href="{{ route('seller.products.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>

                <form action="{{ route('seller.products.destroy', $p->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                        Hapus
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection