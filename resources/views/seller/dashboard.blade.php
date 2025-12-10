@extends('layouts.app')

@section('title', 'Seller Dashboard')

@section('content')
<div class="container py-4">

    {{-- Judul --}}
    <h2 class="fw-bold mb-4">Seller Dashboard</h2>

    {{-- Info Seller --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold mb-1">Welcome, {{ $seller->name }} 👋</h5>
            <small class="text-muted">
                Toko: <strong>{{ $seller->store->name ?? '-' }}</strong>
            </small>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h3 class="fw-bold">{{ $products->count() }}</h3>
                    <p class="text-muted">Total Produk</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h3 class="fw-bold">–</h3>
                    <p class="text-muted">Total Penjualan</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h3 class="fw-bold">–</h3>
                    <p class="text-muted">Saldo Toko</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Judul Daftar Produk --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Produk Anda</h4>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Produk</a>
    </div>

    {{-- Grid Produk mirip homepage --}}
    <div class="row g-4">
        @forelse ($products as $product)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">

                    {{-- Gambar Produk --}}
                    @php
                        $img = $product->productImages->first()->image_path ?? 'no-image.png';
                    @endphp

                    <img src="{{ asset('storage/' . $img) }}"
                        class="card-img-top"
                        style="height: 200px; object-fit: cover;">

                    <div class="card-body d-flex flex-column">

                        {{-- Nama Produk --}}
                        <h5 class="fw-bold">{{ $product->name }}</h5>

                        {{-- Kategori --}}
                        <p class="text-muted small">
                            {{ $product->productCategory->name ?? 'Tanpa Kategori' }}
                        </p>

                        {{-- Harga --}}
                        <p class="fw-bold text-primary">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        {{-- Tombol --}}
                        <div class="mt-auto">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">Detail</a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Belum ada produk.</p>
        @endforelse
    </div>

</div>
@endsection
