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

<div class="product-list">
    @foreach($products as $p)
        <div class="product-card">
            <h3>{{ $p->name }}</h3>
            <p>Harga: Rp {{ number_format($p->price) }}</p>
            <p>Kategori: {{ $p->productCategory->name ?? '-' }}</p>
            <p>Toko: {{ $p->store->name ?? '-' }}</p>

            {{-- tampilkan gambar pertama --}}
            @if($p->productImages->first())
                <img src="{{ asset('storage/' . $p->productImages->first()->image_path) }}" width="150">
            @endif

            <a href="/product/{{ $p->id }}">Lihat Detail</a>
        </div>
    @endforeach
</div>
@endsection