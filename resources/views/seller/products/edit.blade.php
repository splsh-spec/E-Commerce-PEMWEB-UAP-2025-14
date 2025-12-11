@extends('layouts.seller')

@section('title', 'Edit Produk')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">

        <h3 class="mb-3">Edit Produk</h3>

        <form method="POST" action="{{ route('seller.products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="product_category_id" class="form-control">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" 
                        {{ $product->product_category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="name" class="form-control" value="{{ $product->name }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Kondisi</label>
                <select name="condition" class="form-control">
                    <option value="new" {{ $product->condition == 'new' ? 'selected' : '' }}>Baru</option>
                    <option value="used" {{ $product->condition == 'used' ? 'selected' : '' }}>Bekas</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="price" class="form-control" value="{{ $product->price }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Berat (gram)</label>
                    <input type="number" name="weight" class="form-control" value="{{ $product->weight }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}">
                </div>
            </div>

            {{-- Gambar Lama --}}
            <div class="mb-2">
                <label class="form-label fw-bold">Gambar Lama</label>
                <div class="d-flex flex-wrap">
                    @foreach ($product->productImages as $img)
                    <img src="{{ asset('storage/' . $img->image_url) }}" 
                         class="m-2 border rounded"
                         style="height: 120px;">
                    @endforeach
                </div>
                <p class="text-muted small">Semua gambar lama akan dihapus jika kamu upload gambar baru.</p>
            </div>

            {{-- Upload Gambar Baru --}}
            <div class="mb-3">
                <label class="form-label">Upload Gambar Baru</label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*" onchange="previewImages(event)">
            </div>

            <div class="preview-images d-flex flex-wrap"></div>

            <button class="btn btn-warning mt-3">Update</button>
        </form>

    </div>
</div>

<script>
    function previewImages(event) {
        const container = document.querySelector('.preview-images');
        container.innerHTML = "";
        [...event.target.files].forEach(file => {
            let img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = "m-2 border rounded";
            img.style.height = "120px";
            container.appendChild(img);
        });
    }
</script>

@endsection