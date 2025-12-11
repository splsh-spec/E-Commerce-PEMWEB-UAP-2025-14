@extends('layouts.seller')

@section('content')
<h1 class="text-2xl font-bold mb-4">
    Dashboard Seller – {{ $seller->store->name }}
</h1>

@if (session('error'))
    <div class="bg-red-500 text-white p-2 mb-3">{{ session('error') }}</div>
@endif

<h2 class="text-xl font-bold mb-2">Produk Anda</h2>

<a href="{{ route('seller.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
    Tambah Produk Baru
</a>

<table class="w-full mt-4 border">
    <thead>
        <tr>
            <th class="border p-2">Nama</th>
            <th class="border p-2">Kategori</th>
            <th class="border p-2">Gambar</th>
            <th class="border p-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td class="border p-2">{{ $product->name }}</td>
            <td class="border p-2">{{ $product->productCategory->name ?? '-' }}</td>
            <td class="border p-2">
                @if($product->productImages->first())
                    <img src="{{ asset('storage/'.$product->productImages->first()->image) }}" 
                         class="w-20 h-20 object-cover">
                @else
                    Tidak ada
                @endif
            </td>
            <td class="border p-2">
                <a href="{{ route('seller.products.edit', $product->id) }}" class="text-blue-600">Edit</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection