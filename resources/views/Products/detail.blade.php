@extends('layouts.app')

@section('content')
<div style="max-width:900px;margin:30px auto;padding:20px;background:#fff;
            border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.1);">

    {{-- Header Produk --}}
    <h1 style="font-size:28px;font-weight:bold;margin-bottom:10px;">
        {{ $product->name }}
    </h1>

    {{-- Container utama --}}
    <div style="display:flex;gap:30px;flex-wrap:wrap;">

        {{-- Gambar utama / placeholder --}}
        <div style="flex:1;min-width:250px;">
            @if($product->productImages->first())
                <img src="{{ asset('storage/' . $product->productImages->first()->image_path) }}"
                     style="width:100%;border-radius:8px;object-fit:cover;">
            @else
                <div style="width:100%;height:250px;background:#f3f3f3;border-radius:8px;
                            display:flex;align-items:center;justify-content:center;color:#888;">
                    Tidak ada gambar.
                </div>
            @endif

            {{-- Thumbnail --}}
            @if($product->productImages->count() > 1)
                <div style="display:flex;gap:10px;margin-top:10px;flex-wrap:wrap;">
                    @foreach($product->productImages as $img)
                        <img src="{{ asset('storage/' . $img->image_path) }}"
                             style="width:70px;height:70px;border-radius:6px;object-fit:cover;border:1px solid #ddd;">
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Detail produk --}}
        <div style="flex:1;min-width:250px;">

            <p style="margin:5px 0;font-size:18px;">
                <strong>Harga:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>

            <p style="margin:5px 0;font-size:16px;">
                <strong>Kategori:</strong> {{ $product->productCategory->name ?? '-' }}
            </p>

            <p style="margin:5px 0;font-size:16px;">
                <strong>Toko:</strong> {{ $product->store->name ?? '-' }}
            </p>

            {{-- Tombol Beli --}}
            <div style="margin-top:20px;">
                <a href="{{ route('member.checkout.index', ['productId' => $product->id]) }}"
                   style="display:inline-block;padding:12px 25px;background:#28a745;
                          color:white;border-radius:6px;font-size:16px;text-decoration:none;
                          font-weight:600;">
                    Beli Sekarang
                </a>
            </div>

        </div>
    </div>

    <hr style="margin:30px 0;">

    {{-- Review Produk --}}
    <h2 style="font-size:22px;font-weight:bold;margin-bottom:15px;">Ulasan Pelanggan</h2>

    @forelse($product->productReviews as $review)
        <div style="border:1px solid #e5e5e5;padding:15px;border-radius:8px;margin-bottom:15px;">
            <strong>{{ $review->user->name }}</strong>
            <p style="margin-top:5px;">{{ $review->review_text }}</p>
        </div>
    @empty
        <p style="color:#777;">Belum ada ulasan.</p>
    @endforelse
</div>
@endsection