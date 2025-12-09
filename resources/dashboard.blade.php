@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4 fw-bold">Seller Dashboard</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-1">Welcome, {{ $seller->name }} 👋</h5>
            <small class="text-muted">Store ID: {{ $seller->store_id }}</small>
        </div>
    </div>

    <!-- Statistik Singkat -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h3 class="fw-bold">{{ $products->count() }}</h3>
                    <p class="text-muted">Total Produk</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h3 class="fw-bold">–</h3>
                    <p class="text-muted">Total Penjualan</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h3 class="fw-bold">–</h3>
                    <p class="text-muted">Saldo Toko</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Produk -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Produk Anda</h4>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Produk</a>
    </div>

    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    @if($product->productImages->first())
                        <img src="{{ asset('storage/' . $product->productImages->first()->image_path) }}"
                             class="card-img-top" style="height: 180px; object-fit: cover;">
                    @else
                        <div class="bg-light text-center py-5 text-muted">No Image</div>
                    @endif

                    <div class="card-body">
                        <h5 class="fw-bold">{{ $product->name }}</h5>
                        <p class="text-muted mb-1">Kategori: {{ $product->productCategory->name ?? '-' }}</p>
                        <p class="fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">Detail</a>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Belum ada produk.</p>
        @endforelse
    </div>

</div>
@endsection
