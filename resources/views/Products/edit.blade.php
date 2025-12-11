@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Edit Produk</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Produk -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Produk</label>
                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                </div>

                <!-- Harga -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Harga</label>
                    <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
                </div>

                <!-- Stok -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Stok</label>
                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                </div>

                <!-- Kategori -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Kategori</label>
                    <select name="product_category_id" class="form-control" required>
                        @foreach(\App\Models\ProductCategory::all() as $cat)
                            <option value="{{ $cat->id }}"
                                {{ $product->product_category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>
</div>
@endsection
