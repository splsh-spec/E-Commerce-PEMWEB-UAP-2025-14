@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="fw-bold mb-4">Tambah Produk Baru</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <!-- ALERT -->
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Nama Produk -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nama Produk</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <!-- Kategori -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="product_category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Harga -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>

                    <!-- Stok -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Stok</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>

                <!-- Deskripsi -->
<textarea name="description" class="form-control" required></textarea>

<!-- Berat -->
<input type="number" name="weight" class="form-control" required>

<!-- Kondisi -->
<select name="condition" class="form-control" required>
    <option value="new">Baru</option>
    <option value="second">Bekas</option>
</select>
                    <!-- Upload Gambar Produk -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Gambar Produk</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <!-- store_id milik seller -->
                    <input type="hidden" name="store_id" value="{{ auth()->user()->store->id }}">
                </div>

                {{-- TOMBOL --}}
                <button type="submit" class="btn btn-primary mt-2">Simpan Produk</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary mt-2">Kembali</a>

            </form>

        </div>
    </div>

</div>
@endsection
