@extends('layouts.app')

@section('content')
<h1>Daftar Produk</h1>

<form method="GET" action="/">
    <select name="category" onchange="this.form.submit()">
        <option value="">Semua Kategori</option>

        @foreach($categories as $c)
            <option value="{{ $c->id }}" {{ $selectedCategory == $c->id ? 'selected' : '' }}>
                {{ $c->name }}
            </option>
        @endforeach
    </select>
</form>

<div class="product-list" style="display:flex;flex-wrap:wrap;gap:20px;margin-top:20px;">
    @forelse($products as $p)
        <div class="product-card" style="border:1px solid #ccc;padding:15px;width:250px;">
            
            <h3>{{ $p->name }}</h3>

            <p>Harga: Rp {{ number_format($p->price) }}</p>

            <p>Kategori: {{ $p->productCategory->name ?? '-' }}</p>

            <p>Toko: {{ $p->store->name ?? '-' }}</p>

            {{-- Gambar Produk --}}
            @if($p->productImages->first())
                <img src="{{ asset('storage/' . $p->productImages->first()->image_path) }}" width="200">
            @endif

            <a href="/product/{{ $p->slug }}">Lihat Detail</a>
        </div>
    @empty
        <p>Tidak ada produk ditemukan.</p>
    @endforelse
</div>

@endsection