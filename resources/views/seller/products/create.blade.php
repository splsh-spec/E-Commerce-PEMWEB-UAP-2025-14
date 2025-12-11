@extends('layouts.seller')

@section('title', 'Tambah Produk')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">

        <h3 class="mb-3">Tambah Produk</h3>

        <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="product_category_id" class="form-control">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="name" class="form-control" placeholder="Nama produk">
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Deskripsi produk"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Kondisi</label>
                <select name="condition" class="form-control">
                    <option value="new">Baru</option>
                    <option value="used">Bekas</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="price" class="form-control" placeholder="Harga">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Berat (gram)</label>
                    <input type="number" name="weight" class="form-control" placeholder="Berat produk">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" placeholder="Jumlah stok">
                </div>
            </div>

            <!-- Upload Gambar -->
            <div class="mb-3">
                <label class="form-label">Upload Gambar Produk</label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*" onchange="previewImages(event)">
            </div>

            <div class="preview-images d-flex flex-wrap"></div>

            <button class="btn btn-primary mt-3">Simpan</button>
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