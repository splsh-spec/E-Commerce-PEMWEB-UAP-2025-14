@extends('layouts.app')

@section('content')

@auth
    @if(auth()->user()->role === 'member')

        {{-- Tampilkan Saldo --}}
        <div style="margin-bottom:20px;padding:12px;background:#f8f9fa;border:1px solid #ddd;border-radius:5px;">
            <strong>Saldo Anda:</strong> 
            <span style="color:#28a745;font-weight:bold;">
                Rp {{ number_format($balance, 0, ',', '.') }}
            </span>
        </div>

        <a href="{{ route('wallet.topup') }}" 
           style="display:inline-block;margin-bottom:20px;padding:10px 15px;background:#28a745;color:white;border-radius:5px;">
            Top Up Saldo
        </a>

        <a href="{{ route('transaction.index') }}" 
           style="display:inline-block;margin-bottom:20px;padding:10px 15px;background:#007bff;color:white;border-radius:5px;">
            Riwayat Transaksi
        </a>
    @endif
@endauth

<h1>Daftar Produk</h1>

{{-- Filter kategori --}}
<form method="GET" action="{{ route('home') }}">
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
            
            {{-- Gambar Produk --}}
            @if($p->productImages->first())
                <img src="{{ asset('storage/' . $p->productImages->first()->image) }}"
                     style="width:100%;height:180px;object-fit:cover;border-radius:5px;margin-bottom:10px;">
            @else
                <div style="width:100%;height:180px;background:#f4f4f4;border-radius:5px;
                            display:flex;align-items:center;justify-content:center;color:#777;margin-bottom:10px;">
                    Tidak ada gambar
                </div>
            @endif

            <h3>{{ $p->name }}</h3>
            <p>Harga: Rp {{ number_format($p->price) }}</p>
            <p>Kategori: {{ $p->productCategory->name ?? '-' }}</p>
            <p>Toko: {{ $p->store->name ?? '-' }}</p>

            <a href="{{ route('product.detail', $p->slug) }}">Lihat Detail</a>
        </div>
    @empty
        <p>Tidak ada produk ditemukan.</p>
    @endforelse
</div>
@endsection