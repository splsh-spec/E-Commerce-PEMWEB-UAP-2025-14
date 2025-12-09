@extends('layouts.app')

@section('title', 'Dashboard Seller')

@section('content')

<div class="container py-4">

    {{-- Header Dashboard --}}
    <div class="mb-4">
        <h2 class="fw-bold">Dashboard Seller</h2>
        <p class="text-muted">Selamat datang, {{ $seller->name }}!</p>
    </div>

    {{-- Informasi Toko --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-2">Informasi Toko</h5>
            
            @if ($seller->store)
                <p class="mb-1"><strong>Nama Toko:</strong> {{ $seller->store->name }}</p>
                <p class="mb-1"><strong>Alamat:</strong> {{ $seller->store->address }}</p>
                <p class="mb-1"><strong>Deskripsi:</strong> {{ $seller->store->description ?? '-' }}</p>
            @else
                <p class="text-danger">Anda belum memiliki toko.</p>
            @endif
        </div>
    </div>

    {{-- Daftar Produk --}}
    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold">Produk Anda</h5>
                <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-sm">
                    + Tambah Produk
                </a>
            </div>

            @if ($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        @php
                                            $img = $product->productImages->first()->image_path ?? 'no-image.png';
                                        @endphp
                                        <img src="{{ asset('storage/' . $img) }}" 
                                             alt="image" width="60" class="rounded">
                                    </td>

                                    <td>{{ $product->name }}</td>

                                    <td>
                                        {{ $product->productCategory->name ?? 'Tanpa Kategori' }}
                                    </td>

                                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>

                                    <td>{{ $product->stock }}</td>

                                    <td>
                                        <a href="{{ route('seller.products.edit', $product->id) }}" 
                                           class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <form action="{{ route('seller.products.destroy', $product->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Hapus produk ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Belum ada produk. Tambahkan produk pertama Anda.</p>
            @endif

        </div>
    </div>

</div>

@endsection
